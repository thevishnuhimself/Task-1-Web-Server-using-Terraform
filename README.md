# Task
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



# Vishnu


bdsjkfabakbskfdkabsfkaks

        jskflksflkslflslfsjlsjlfjsl
      
     
**bold**

![](/images/moon.jpg)

_italics_

_**bold-italics**_
