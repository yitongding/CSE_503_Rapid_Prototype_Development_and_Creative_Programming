<!DOCTYPE html>
<html>
    <head>
        <title>Calculation Result</title>
    </head>
    
    <body>
        <?php
            $firstnum = $_POST['firstnum'];
            $secondnum = $_POST['secondnum'];
            if (!isset($_POST['cal_type']))
            {
                printf("Error, invalid input.");
                exit;
            }
            $caltype = $_POST['cal_type'];
            $result = 0;
            if ($secondnum == 0 || is_null($secondnum) || is_null($firstnum) || is_null($caltype) ) 
            {
                printf("Error, invalid input.");
                exit;
            }
            $flag = "good";
            switch ($caltype) {
                case "add": 
                    $result = $firstnum + $secondnum;
                    break;
                case "sub":
                    $result = $firstnum - $secondnum;
                    break;
                case "mul":
                    $result = $firstnum * $secondnum;
                    break;
                case "div":
                    $result = $firstnum / $secondnum;
                    break;
                default:
                    $flag = "bad";
            }
            if ($flag == "bad") {
                echo "Error, opration undefined.";
            }
            else {
                printf("Result: %.2f", $result);
            }
        ?>
        
        <form action="./calculator.html" method=POST>
            <input type="submit" value="Return">
        </form>
    </body>
    
</html>