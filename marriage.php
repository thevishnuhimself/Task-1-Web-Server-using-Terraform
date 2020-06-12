<?php
$b =20;
$g =17;
//if($b>=21 and $g>=18)
//{
?>
<!DOCTYPE html>
<html lang="en">
<body>
    <?php
    if($b>=21 and $g<18)
        {
        echo "<font color= '#b70000'> Only Boy is eligible to marry.";
        }
    elseif($b<21 and $g>=18)
            {
    
        echo "<font color= 'orange'> Only Girl is eligible to marry.";
            }
    elseif($b>=21 and $g>=18)
        echo"<font color= 'green'> You both are eligible to marry.";
    
        else
        {
            echo "<font color= 'red'>You both are not eligible to marry.";
        }
    ?>
</body>
</html>
<?php
//}
//else
//{
//    echo " You are not eligible for marriage.";
//}