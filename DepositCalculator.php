<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Deposit Calculator</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <style>
        .errorMsg {
            color: red;
            margin-left: 10px;
        }
    </style>
</head>
<body class="container">

<?php error_reporting(E_ALL ^ E_NOTICE); //specify All errors and warnings are displayed?>
<?php
    session_start(); //start PHP session!
    extract($_POST);

    $valid = false;
    $amountErr = "";
    $rateErr = "";
    $yearErr = "";
    $nameErr = "";
    $postcodeErr = "";
    $phoneErr = "";
    $emailErr = "";
    $contactErr = "";
    $timeErr = "";

    if(isset($btnCalculate)) //check if the page is requested due to the form submission, NOT the first time request
    {
        $amountErr = ValidatePrincipal($amount);
        $rateErr = ValidateRate($rate);
        $yearErr = ValidateYears($year);
        $nameErr = ValidateName($name);
        $postcodeErr = ValidatePostalCode($postcode);
        $phoneErr = ValidatePhone($phone);
        $emailErr = ValidateEmail($email);
        $contactErr = ValidateContact($contact);
        $timeErr = ValidateTime($contact, $time);

        if(!$amountErr && !$rateErr && !$yearErr && !$nameErr && !$postcodeErr && !$phoneErr && !$emailErr && !$contactErr && !$timeErr)
        {
            $valid = true;
        }
    }
    elseif(isset($btnReset))
    {
        header("Location: DepositCalculator.php");
        exit();
    }


    //preserve the user input so users don't enter values again
//    if(isset($amount))
//    {
//        $amountValue  = $amount;
//    }
//    else
//    {
//        $amountValue = '';
//    }
//  is the same as
    $amountValue = $amount ?? "";
    $rateValue = $rate ?? "";
    $nameValue = $name ?? "";
    $postcodeValue = $postcode ?? "";
    $phoneValue = $phone ?? "";
    $emailValue = $email ?? "";
    $yearValue = $year ?? "1";
    $contact = $contact ?? "phone";
    $time = $time ?? "";

    if(!$valid)
    {
        print <<<EOS
            <h1 class="my-3">Deposit Calculator</h1>
            <form action="DepositCalculator.php" method="post"> <!--a form can be submitted to the same page itself to display error msg-->
                <div class="row form-group">
                    <label for="amount" class="col-md-2">Principal Amount: </label>
                    <input type="text" id="amount" name="amount" class="form-control col-md-3" value="$amountValue">
                    <span class="errorMsg">$amountErr</span>
                </div>
                <div class="row form-group">
                    <label for="rate" class="col-md-2">Interest Rate (%): </label>
                    <input type="text" id="rate" name="rate" class="form-control col-md-3" value="$rateValue">
                    <span class="errorMsg">$rateErr</span>
                </div>
                <div class="row form-group">
                    <label for="year" class="col-md-2">Years to Deposit: </label>
                    <select id="year" name="year" class="form-control col-md-3">
        EOS;

                    for ($y = 1; $y <= 20; $y++)
                    {
                        echo "<option value='$y'", ($yearValue == $y) ? "selected>" : ">", $y, "</option>";
                    }

        print <<<EOS
                    </select>
                    <span class="errorMsg">$yearErr</span>
                </div>
                <hr>
                <div class="row form-group">
                    <label for="name" class="col-md-2">Name: </label>
                    <input type="text" id="name" name="name" class="form-control col-md-3" value="$nameValue">
                    <span class="errorMsg">$nameErr</span>
                </div>
                <div class="row form-group">
                    <label for="postcode" class="col-md-2">Postal Code: </label>
                    <input type="text" id="postcode" name="postcode" class="form-control col-md-3" value="$postcodeValue">
                    <span class="errorMsg">$postcodeErr</span>
                </div>
                <div class="row form-group">
                    <label for="phone" class="col-md-2">Phone Number: <br>(nnn-nnn-nnnn)</label>
                    <input type="text" id="phone" name="phone" class="form-control col-md-3" value="$phoneValue">
                    <span class="errorMsg">$phoneErr</span>
                </div>
                <div class="row form-group">
                    <label for="email" class="col-md-2">Email Address: </label>
                    <input type="text" id="email" name="email" class="form-control col-md-3" value="$emailValue">
                    <span class="errorMsg">$emailErr</span>
                </div>
                <hr>
        EOS;
        $contactMethod_1 = ($contact == "phone") ? "checked" : "";
        $contactMethod_2 = ($contact == "email") ? "checked" : "";
        $contactTime_1 = (in_array("morning", (array)$time)) ? "checked" : "";
        $contactTime_2 = (in_array("afternoon", (array)$time)) ? "checked" : "";
        $contactTime_3 = (in_array("evening", (array)$time)) ? "checked" : "";
        print <<<EOS
                <div class="row form-group">
                    <p class="col-md-3">Preferred contact Method: </p>
                    <div class="form-check col-md-1">
                        <input type="radio" id="radio1" name="contact" value="phone" checked="checked" class="form-check-input" $contactMethod_1>
                        <label for="radio1" class="form-check-label">Phone</label>
                    </div>
                    <div class="form-check col-md-1">
                        <input type="radio" id="radio2" name="contact" value="email" class="form-check-input" $contactMethod_2>
                        <label for="radio2" class="form-check-label">Email</label>
                    </div>
                    <span class="errorMsg">$contactErr</span>
                </div>
                <div class="form-group">
                    <p>If phone is selected, when can we contact you? (check all applicable)</p>
                    <div class="form-check form-check-inline">
                        <input type="checkbox" id="checkbox1" name="time[]" value="morning" class="form-check-input" $contactTime_1>
                        <label for="checkbox1" class="form-check-label">Morning</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="checkbox" id="checkbox2" name="time[]" value="afternoon" class="form-check-input" $contactTime_2>
                        <label for="checkbox2" class="form-check-label">Afternoon</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="checkbox" id="checkbox3" name="time[]" value="evening" class="form-check-input" $contactTime_3>
                        <label for="checkbox3" class="form-check-label">Evening</label>
                    </div>
                    <span class="errorMsg">$timeErr</span>
                </div>
                <button type="submit" name="btnCalculate" class="btn btn-primary mr-2">Calculate</button>
                <button type="submit" name="btnReset" class="btn btn-primary">Clear</button>
            </form>

        EOS;
    }
    else
    {
            echo "<h1>Thank you, <span style='color: blue; font-weight: bold;'>$name</span>, for using our deposit calculation tool.</h1>";
            if($contact == "phone")
            {
                $timeMsg = implode(" or ", $time);
                echo "<p>Our customer service department will call you tomorrow $timeMsg at $phone.</p>";
            }
            elseif($contact == "email")
            {
                echo "<p>An email about the details of our GIC has been sent to $email.</p><br>";
            }

        print <<<EOS
            <p>The following is the result of the calculation:</p>
            <table class='table table-striped'>
                    <tr>
                        <th scope='col'>Year</th>
                        <th scope='col'>Principal at Year Start</th>
                        <th scope='col'>Interest for the Year</th>
                    </tr>
            EOS;
        for($i = 1; $i <= $year; $i++)
        {
            $interest = $amount * ($rate/100);

            print"<tr><td>$i</td><td>$";
            printf("%.2f", $amount);
            print"</td><td>$";
            printf("%.2f", $interest);
            print"</td></tr>";

            $amount = $amount + $interest;

        }
        echo "</table>";
        echo "<a href='DepositCalculator.php'>Back</a>";
    }


function ValidatePrincipal($amount): string
{
    if(!trim($amount)) //empty string is translated to logical false
    {
        return "Principal amount can not be blank";
    }
    elseif(!is_numeric($amount))
    {
        return "Principal amount must be numeric";
    }
    elseif($amount <= 0)
    {
        return "Principal amount must be greater than 0";
    }
    else
    {
        return "";
    }
}

function ValidateRate($rate): string
{
    if(trim($rate) == "")
    {
        return "Interest rate can not be blank";
    }
    elseif(!is_numeric($rate))
    {
        return "Interest rate must be numeric";
    }
    elseif($rate < 0)
    {
        return "Interest rate must be non-negative";
    }
    else
    {
        return "";
    }
}

function ValidateYears($years): string
{
    if(!trim($years))
    {
        return "Number of years to deposit can not be blank";
    }
    elseif(!is_numeric($years))
    {
        return "Number of years to deposit must be numeric";
    }
    elseif($years < 1 || $years > 20)
    {
        return "Number of years to deposit must be a numeric between 1 and 20";
    }
    else
    {
        return "";
    }
}

function ValidateName($name): string
{
    if(!trim($name))
    {
        return "Name can not be blank";
    }
    else
    {
        return "";
    }
}

function ValidatePostalCode($postalCode): string
{
    $regex = "/[a-z][0-9][a-z]\s*[0-9][a-z][0-9]/i";
    if(!trim($postalCode))
    {
        return "Postal code can not be blank";
    }
    elseif(!preg_match($regex, $postalCode))
    {
        return "Incorrect postal code";
    }
    else
    {
        return "";
    }
}

function ValidatePhone($phone): string
{
    $regex = "/^([2-9]\d{2})-([2-9]{3})-(\d{4})$/";
    if(!trim($phone))
    {
        return "Phone number can not be blank";
    }
    elseif(!preg_match($regex, $phone))
    {
        return "Incorrect phone number";
    }
    else
    {
        return "";
    }
}

function ValidateEmail($email): string
{
    $regex = "/\b[a-z0-9._%+-]+@(([a-z0-9-]+)\.)+[a-z]{2,4}\b/i";
    if(!trim($email))
    {
        return "Email can not be blank";
    }
    elseif(!preg_match($regex, $email))
    {
        return "Incorrect email";
    }
    else
    {
        return "";
    }
}

function ValidateContact($contact): string
{
    if(!$contact)
    {
        return "Preferred contact method can not be blank";
    }
    else
    {
        return "";
    }
}

function ValidateTime($contact, &$time): string //using & to pass array by reference
{
    if($contact == "phone" && !isset($time))
    {
        return "When preferred contact method is phone, you have to select one or more contact times";
    }
    else
    {
        return "";
    }
}

?>

</body>
</html>