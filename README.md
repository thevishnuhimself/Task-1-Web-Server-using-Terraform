# Task-1
**Hybrid Multi Cloud Computing Task**<br>
So, let's start....<br>
In this task, I am gonna launch a webserver using terrafom. <br>
**Procedure-1**   First, Configure your profile for access of AWS in your local system.<br>

                      aws configure --profile Vishnu <br>
                      AWS Access Key ID [****************IDY6]:<br>
                      AWS Secret Access Key [****************j/Pot]:<br>
                      Default region name [ap-south-1]:<br>
                      Default output format [None]:<br>

**Procedure-2**   EC2 instance to be launched in this part using Redhat 8 AMI. Here, through Remote Execute Provisioner, I installed and configured Apache Web Services. I have used a key and a security group also for the firewall. In the security group enabled the SSH on port 22 & also do the same for HTTP enabled on port 80. Here, the code - 

                        provider "aws" { 
                        region 		= "ap-south-1"
                        profile 	= "test"
                      }

                      resource "aws_instance" "my_ins" {
                        ami 		= "ami-052c08d70def0ac62"
                        instance_type	= "t2.micro"	
                        key_name	= "newkey"
                        security_groups	= [ "launch-wizard-1" ]

                       connection {
                        type	= "ssh"
                        user	= "ec2-user"
                        private_key = file("C:/Users/This PC/Downloads/newkey.pem")
                        host	= aws_instance.my_ins.public_ip
                       }

                       provisioner "remote-exec" {
                         inline = [
                        "sudo yum install php httpd git -y",
                        "sudo systemctl restart httpd",
                        "sudo systemctl enable httpd",

                        ]
                       }

                       tags = {
                         Name = "my_ins"
                       }
                      }


**Procedure-3** Here, I am creating a volume EBS. I have created a volume of 1 GiB. We have to launch our EBS volume in the same zone in which our instance is launched otherwise they can't be connected. To resolve this, I have use the availability zone of the instance & entered it here.

                       resource "aws_ebs_volume" "vol_ins" {
                        availability_zone	= aws_instance.my_ins.availability_zone
                        size			= 1

                       tags = {
                         Name = "my_volume"
                        }
                      }
 **Procedure-4** Now, I have mount our EBS volume to the folder /var/ww/html so that it can be deployed by the Apache Web Server. I have downloaded the code from Github.     
 
 
                        resource "null_resource" "mount" {
                        depends_on = [
                          aws_volume_attachment.ebs_att,
                           ]

                          connection {
                          type		= "ssh"
                          user		= "ec2-user"
                          private_key	= file("C:/Users/This PC/Downloads/newkey.pem")
                          host		= aws_instance.my_ins.public_ip
                           }


                        provisioner "remote-exec" {
                          inline = [ 
                           "sudo mkfs.ext4 /dev/xvdd",
                           "sudo mount /dev/www/html/*",
                           "sudo git clone https://github.com/vishnu7455/Task-1-.git /var/www/html/"
                          ]
                           }
                        }
                      
 **Procedure-5 (Optional)** Here, the public ip of my instance and I also stored it locally in my PC to used ot later (likely).                    


                        resource "null_resource" "ip_save" {
                          provisioner "local-exec"  {
                            command = "echo ${aws_instance.my_ins.public_ip} > public_ip.txt"
                          }
                        }


 **Procedure-6** Now, I am creating a S3 bucket on AWS -
 
                         resource "aws_s3_bucket" "vishnu_bucket" {
                              bucket = "pachauriji"
                              acl    = "private"
                              
                              tags = {
                                Name        = "vishnu7455"
                              }
                            }
                             locals {
                                s3_origin_id = "myS3Origin"
                              }

 **Procedure-7**  Now, I am uploading a image as a file only in S3 bucket. 
 
                           resource "aws_s3_bucket_object" "image" {
                                bucket = "${aws_s3_bucket.vishnu_bucket.id}"
                                key    = "tst_image"
                                source = "C:/Users/This PC/Documents/moon.jpg"
                                acl    = "public-read"
                              }

 
**Procedure-8** Now, its time to build a CloudFront and attach it to the S3 bucket. We do this for the speedy delivery of content using the edge locations from AWS across the world. 


                           resource "aws_cloudfront_distribution" "my_cloud" {
                                 origin {
                                       domain_name = "${aws_s3_bucket.vishnu_bucket.bucket_regional_domain_name}"
                                       origin_id   = "${local.s3_origin_id}"

                               custom_origin_config {

                                       http_port = 80
                                       https_port = 80
                                       origin_protocol_policy = "match-viewer"
                                       origin_ssl_protocols = ["TLSv1", "TLSv1.1", "TLSv1.2"] 
                                      }
                                    }
                                       enabled = true

                               default_cache_behavior {

                                       allowed_methods  = ["DELETE", "GET", "HEAD", "OPTIONS", "PATCH", "POST", "PUT"]
                                       cached_methods   = ["GET", "HEAD"]
                                       target_origin_id = "${local.s3_origin_id}"

                               forwarded_values {

                                     query_string = false

                               cookies {
                                        forward = "none"
                                       }
                                  }

                                        viewer_protocol_policy = "allow-all"
                                        min_ttl                = 0
                                        default_ttl            = 3600
                                        max_ttl                = 86400

                              }
                                restrictions {
                                       geo_restriction {
                                         restriction_type = "none"
                                        }
                                   }
                               viewer_certificate {
                                     cloudfront_default_certificate = true
                                     }
                              }

Now, I visit the /var/www/html and update the image link with the link from CloudFront(manually). 


**Procedure-9**  Now, finalize all the code correctiveness and write a terraform code for automatically backup of public IP of the instance and open it in any browser. Our website which is present in /var/www/html will be opened here. 

                                resource "null_resource" "local_exec" {



                                depends_on = [
                                  null_resource.mount,
                                  ]

                                  provisioner "local-exec" {
                                    command = "start chrome ${aws_instance.my_ins.public_ip}"
                                    }
                                }
**At last, your page is opened now**<br>
**I always welcome your feedback or any suggestion...**

      




