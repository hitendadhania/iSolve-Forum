<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <style>
        #ques {
            min-height: 400px;
        }
    </style>

    <title>iSolve - Coding Solutions</title>
</head>

<body>
    <?php include 'partials/_dbconnect.php'; ?>
    <?php include 'partials/_header.php'; ?>
    <?php
    $id = $_GET['catid'];
    $sql = "SELECT * FROM categories WHERE category_id = '$id'";
    $result = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $catname = $row['category_name'];
        $catdesc = $row['category_description'];
    }

    ?>

    <?php
    $showAlert = false;
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == 'POST') {
        // Insert thread into db 
        $th_title = $_POST['threadtitle'];
        $th_desc = $_POST['threaddesc'];

        $th_title = str_replace("<", "$lt;" , $th_title);
        $th_title = str_replace(">", "$gt;" , $th_title);

        $th_desc = str_replace("<", "$lt;" , $th_desc);
        $th_desc = str_replace(">", "$gt;" , $th_desc);

        $sno = $_POST['sno'];
        $sql = "INSERT INTO threads (thread_title, thread_description, thread_cat_id, thread_user_id, `timestamp`) VALUES ('$th_title', '$th_desc', '$id', '$sno', current_timestamp())";
        $result = mysqli_query($con, $sql);
        $showAlert = true;
        if ($showAlert) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success! </strong>Your Question has been submitted. Please wait for community respond to your question.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                    </div>';
        }
    }

    ?>
    <!-- Category Containers -->
    <div class="container my-4">
        <div class="jumbotron">
            <h1 class="display-4">Welcome to <?php echo $catname; ?> Forums</h1>
            <p class="lead"><?php echo $catdesc; ?>
            </p>
            <hr class="my-4">
            <p>This Forum is for sharing knowledge to each other.
                No Spam / Advertising / Self-promote in the forums.
                Do not post copyright-infringing material.
                Do not post “offensive” posts, links or images.
                Remain respectful of other members at all times.</p>
            <a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a>
        </div>
    </div>

    <?php
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {

        echo '<div class="container">
                <h2 class="py-2">Start a Discussion</h2>
                    <form action=" ' . $_SERVER['REQUEST_URI'] . '" method="POST">
                        <div class="form-group">
                            <label for="threadtitle">Title</label>
                            <input type="text" class="form-control" id="threadtitle" name="threadtitle" aria-describedby="threadtitleHelp">
                            <small id="emailHelp" class="form-text text-muted">Kepp your title as short and simple as possible</small>
                        </div>
                        <input type="hidden" name="sno" value="'.$_SESSION['sno'].'">
                        <div class="form-group">
                            <label for="threaddesc">Elaborate Your Problem</label>
                            <textarea type="text" class="form-control" name="threaddesc" id="threaddesc" rows="5"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>';
    } else {
        echo '
              <div class="container">
              <h2 class="py-2">Start a Discussion</h2>
                <p class="lead">
                You have login to start a discussion
                </p>
              </div>';
    }
    ?>

    <div class="container mb-5" id="ques">
        <h1 class="py-2">Browse Questions</h1>
        <?php
        $id = $_GET['catid'];
        $sql = "SELECT * FROM threads WHERE thread_cat_id = '$id'";
        $result = mysqli_query($con, $sql);
        $noResult = true;
        while ($row = mysqli_fetch_assoc($result)) {
            $noResult = false;
            $title = $row['thread_title'];
            $desc = $row['thread_description'];
            $thread_id = $row['thread_id'];
            $thread_time = $row['timestamp'];
            $thread_user_id = $row['thread_user_id'];

            $sql2 = "SELECT user_email FROM users WHERE sno = '$thread_user_id'";
            $result2 = mysqli_query($con, $sql2);
            $row2 = mysqli_fetch_assoc($result2);


            echo '<div class="media my-3">
            <img src="images/defaultuser.jpg" width="50px" height="46px" class="mr-3" alt="...">
            <div class="media-body">
                
                <h5 class="mt-0 my-1"><a class="text-dark" href="thread.php?threadid=' . $thread_id . '">' . $title . '</a></h5>
                ' . $desc . '
            </div>
            <p class="font-weight-bold my-0">' . $row2['user_email'] . ' at ' . $thread_time . '</p>
        </div>';
        }
        if ($noResult) {
            echo '<div class="jumbotron jumbotron-fluid">
                    <div class="container">
                    <p class="display-4">No Questions Here!!</p>
                    <p class="lead">Be the First person to ask the question</p>
                    </div>
                </div>';
        }
        ?>

    </div>
    <?php include 'partials/_footer.php'; ?>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>
</body>

</html>