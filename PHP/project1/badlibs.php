<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Badlibs</title>
</head>
<body>
    <form method="POST">
        <p>Noun <br/>
        <input type="text" size="50" name="noun"></p>
        <p>Verb <br/>
        <input type="text" size="50" name="verb"></p>
        <p>Adverb <br/>
        <input type="text" size="50" name="adverb"></p>
        <p>Adjective <br/>
        <input type="text" size="50" name="adjective"></p>
        <p>
        <input type="submit" name="submit" value="Submit">
        <input type="reset" value="Clear">
        </p>
    </form>
    <?php 

        if (isset($_POST['submit'])) {
            $noun = $_POST['noun'];
            $verb = $_POST['verb'];
            $adverb = $_POST['adverb'];
            $adjective = $_POST['adjective'];

            // Validate inputs
            if (empty($noun) || empty($verb) || empty($adverb) || empty($adjective)) 
            {
                echo "<p style='color:red;'>You did not enter correct the forms!"
                        . "Please try again.</p>";
            } else {
                // Connect to the database
                $dbc = mysqli_connect('localhost', 'student', 'student', 'project_1')
                        or die("Error connecting to MySQL server.");

                // Insert data into the database
                $story = "A $adjective $noun floated $adverb by. The crowd $verb"
                        . " as it danced in the air, bringing smiles to everyone watching.";
                $query = "INSERT INTO Badlibs (noun, adjective, adverb, verb, story) 
                        VALUES ('$noun', '$adjective', '$adverb', '$verb', '$story')";
                $result = mysqli_query($dbc, $query);
                
                if (!$result) {
                    echo("Query Error description: " . mysqli_error($dbc));
                } 

                // create select query
                $query = "SELECT story FROM Badlibs ORDER BY id DESC";
                $result = mysqli_query($dbc, $query);
                ?>
                <ul>
                    <?php
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<li>" . $row['story'] . "</li>";
                        }
                    ?>
                </ul>

                <?php
                // Close the database connection
                mysqli_close($dbc);
                
            }
        }

    ?>
</body>
</html>
