#!/usr/bin/php

    <?php
    $servername = "localhost";
    $username = "Rokas";
    $password = "passwordvisma";
    $dbname = "usersvisma";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($argc == 1) {
    ?>

    Usage:
    <?php echo $argv[0]; ?> <option>

    <option> To register a client, use -register and the following arguments in order: firstname, lastname, email, phonenumber, phonenumber2, comment.
        Example of such query: -register vardenis pavardenis pavyzdys@mail.com 86868686 86868686 Comment.
        In order to update a client, use -update followed by the clients email address and the following arguments in order: firstname, lastname,email,phonenumber,phonenumber2, comment.
        An example of such query: -update pavyzdys@mail.com newname lastname newemail@mail.com phonenumber phonenumber2 Comment.
        To delete a client from the database use -delete followed by the clients email address.
        An example of such query: -delete pavyzdys@mail.com

    <?php
}
else if (in_array($argv[1],array('-register'))) {
        $errors = False;
        if(empty($argv[2])){
            echo("first name is not provided. \n");
            $errors=True;
        }else{$firstname = $argv[2];}
        if(empty($argv[3])){
            echo("last name is not provided. \n");
            $errors=True;
        }else{$lastname = $argv[3];}
        if(empty($argv[4])){
            echo("email is not provided.\n");
            $errors=True;
        }else{$email = $argv[4];
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo("Please provide a valid email address \n");
            $errors=True;
        }}
        if(empty($argv[5])){
            echo("phonenumber is not provided.\n");
            $errors=True;
        }else{$phonenumber = $argv[5];}
        if(empty($argv[6])){
            echo("phonenumber repetition is not provided.\n");
            $errors=True;
        }else{$phonenumber2 = $argv[6];
        if ($phonenumber2 != $phonenumber) {
            echo("phone numbers do not match \n");
        }}
        if(empty($argv[7])){
            echo("Comment is not provided.\n");
            $errors=True;
        }else{$comment = $argv[7];}

        if ($errors == False) {
           $select = mysqli_query($conn, "SELECT `email` FROM `client` WHERE `email` = '$email'") or exit(mysqli_error($connD));
           if(mysqli_num_rows($select)) {
               exit('A client is already registered with this email address.');
           }
           else{
               $sql = "INSERT INTO client (firstname, lastname, email, phonenumber, phonenumber2, comment)
                    VALUES ('$firstname', '$lastname', '$email', '$phonenumber', '$phonenumber2', '$comment')";

               if ($conn->query($sql) === TRUE) {
                   echo "New record created successfully \n";
               }
               else {
                   echo "Error: " . $sql . "<br>" . $conn->error;
               }
           }$conn->close();
}
    }
else if (in_array($argv[1],array('-update'))){
        $errors=False;
        if(empty($argv[2])){
            exit("You must provide which client to update");
        }
        else {
            $clientemail = $argv[2];
            $select = mysqli_query($conn, "SELECT `email` FROM `client` WHERE `email` = '$clientemail'") or exit(mysqli_error($connD));
            if(mysqli_num_rows($select)) {
                if(empty($argv[3])){
                    echo("first name is not provided.\n");
                    $errors=True;
                }else{$firstname = $argv[3];}
                if(empty($argv[4])){
                    echo("last name is not provided.\n");
                    $errors=True;
                }else{$lastname = $argv[4];}
                if(empty($argv[5])){
                    echo("email is not provided.\n");
                    $errors=True;
                }else{$email = $argv[5];
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    echo("$email is not a valid email address \n");
                    $errors=True;
                }}
                if(empty($argv[6])){
                    echo("phonenumber is not provided.\n");
                    $errors=True;
                }else{$phonenumber = $argv[6];}
                if(empty($argv[7])){
                    echo("phonenumber repetition is not provided.\n");
                    $errors=True;
                }else{$phonenumber2 = $argv[7];
                if ($phonenumber2 != $phonenumber) {
                    echo("phone numbers do not match \n");
                    $errors=True;
                }}
                if(empty($argv[8])){
                    echo("Comment is not provided.\n");
                    $errors=True;
                }else{$comment = $argv[8];}
                if ($errors == False) {

                    $sql = "UPDATE client SET firstname='$firstname', lastname='$lastname', email='$email', phonenumber='$phonenumber',
                          phonenumber2='$phonenumber2', comment='$comment' WHERE email = '$clientemail'";

                        if ($conn->query($sql) === TRUE) {
                            echo "record was updated succesfully \n";
                        }
                        else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }
                    $conn->close();
                }
            }
            else{
                exit("no client with such email exists");
            }
        }
}
else if (in_array($argv[1],array('-delete'))) {
    if(empty($argv[2])){
        exit("You must provide which client to remove");
    }
    $email=$argv[2];
    $select = mysqli_query($conn, "SELECT `email` FROM `client` WHERE `email` = '$email'") or exit(mysqli_error($connD));
    if(mysqli_num_rows($select)) {
    $sql = "DELETE FROM client WHERE email='$email'";

    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $conn->close();
}
    else{
        exit("A client with such email does not exist");
    }
    }

    ?>