<?php
    session_start();
    require_once('db.php');

    if(isset($_POST['addBtn'])){
        $title=$_POST['title'];
        $note=$_POST['note'];
        $date=date('Y-m-d H:i:s');
        $createdById=$_SESSION['user']->id;
        $stmt=$db->prepare("INSERT INTO notes(title, note, created_by_id, created_at) VALUES (:title, :note, :created_by_id, :created_at)");
        $stmt->execute(array(
            ':title'=> $title,
            ':note'=> $note,
            ':created_by_id'=>$createdById,
            ':created_at'=>$date
        ));
    }

    

        // Edit Note
        if(isset($_GET['note_id'])){
            $noteId=$_GET['note_id'];
            $stmt=$db->prepare("SELECT * FROM notes WHERE id=$noteId");
            $stmt->execute();
            $editNote=$stmt->fetchObject();
        }

        // Update Note
        if(isset($_POST['updateBtn'])){
            $title=$_POST['title'];
            $note=$_POST['note'];
            $noteId=$_POST['note_id'];

            $stmt=$db->prepare("UPDATE notes SET title='$title',note='$note' WHERE id=$noteId");
            $stmt->execute();
            header("location:index.php");
        }

            // Read Note
            $stmt=$db->prepare("SELECT * FROM notes");
            $stmt->execute();
            $notes=$stmt->fetchAll(PDO::FETCH_OBJ);

            //Delete Note
            if(isset($_POST['deleteBtn'])){
                $noteId=$_POST['note_id'];

                $stmt=$db->prepare("DELETE FROM notes WHERE id=$noteId");
                $stmt->execute();
                header("location:index.php");
            }

            //Copy Note
            if(isset($_POST['copyBtn'])){
                $noteId=$_POST['note_id'];

                $stmt=$db->prepare("SELECT * FROM notes WHERE id=$noteId");
                $stmt->execute();
                $note=$stmt->fetchObject();
                print_r($note);
                if ($note){
                $stmt=$db->prepare("INSERT INTO notes(title, note, created_by_id, created_at) VALUES (:title, :note, :created_by_id, :created_at)");
                $stmt->execute(array(
                    ':title'=> $note->title,
                    ':note'=> $note->note,
                    ':created_by_id'=>$note->created_by_id,
                    ':created_at'=>$note->created_at
                ));

            }

            header('location:index.php');



            }

            if(isset($_POST['logOutBtn'])){
                session_destroy();
                header('location:index.php');
            }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Index</title>
</head>
<body>
<h1 class="fw-2 text-center">Click To Enter</h1>
    <div class="d-flex justify-content-center m-5 ">
        <button class="btn btn-primary mx-2"><a href="register.php" class="text-decoration-none text-black">Register</a></button>
        <button class="btn btn-primary mx-2"><a href="login.php" class="text-decoration-none text-black">Log In</a></button>
        <form method="post">
        <button class="btn btn-primary mx-2  text-black" name="logOutBtn">Log Out</button>
        </form>
        <!-- <button class="btn btn-primary mx-2"><a href="logout.php" class="text-decoration-none text-black">Log Out</a></button> -->
    </div>  
    <?php if(isset($_SESSION['user'])):?>
    <?php if(!isset($_GET['note_id'])):?>
    <div class="card m-5 mx-auto " style="width: 18rem;">
        <div class="card-body">
            <form method="post">
                <h5 class="card-title">
                    <input type="text" name="title" placeholder="Note Title" style="border:none;" class="form-control">
                </h5>
                <p class="card-text">
                    <textarea name="note" id="" cols="30" rows="5" placeholder="Note" style="border:none" class="form-control"></textarea>
                </p>
                <button class="btn btn-primary" name="addBtn">Add</button>
            </form>
        </div>
    </div>
    <?php else :?>
    <div class="card m-5 mx-auto " style="width: 18rem;">
        <div class="card-body">
            <form method="post">
                <h5 class="card-title">
                    <input type="hidden" name="note_id" value="<?php echo $editNote->id?>" >
                    <input type="text" name="title" value="<?php echo $editNote->title?>" style="border:none;" class="form-control">
                </h5>
                <p class="card-text">
                    <textarea name="note" id="" cols="30" rows="5" value="<?php echo $editNote->note?>" style="border:none" class="form-control"><?php echo $editNote->note?></textarea>
                </p>
                <button class="btn btn-primary" name="updateBtn">Update</button>
            </form>
        </div>
    </div>
    <?php endif; ?>
<!-- all cards -->
    <?php
    foreach($notes as $note):?>
    <?php if($_SESSION['user']->id === $note->created_by_id):?>
    <div class="card m-5" style="width: 18rem;">
        <div class="card-body">
            <form method="post">
                <h5 class="card-title">
                    <input type="hidden" name="note_id" value="<?php echo $note->id?>" >
                    <input type="text" name="title" value="<?php echo $note->title?>"  style="border:none;" class="form-control">
                </h5>
                <p class="card-text">
                    <textarea name="note" id="" cols="30" rows="5" value="<?php echo $note->note?>"  style="border:none" class="form-control"><?php echo $note->note?></textarea>
                </p>
                <a href="index.php?note_id=<?php echo $note->id?>" class="btn btn-info ">Edit</a>
                <button name="deleteBtn" class="btn btn-danger"  float-end ">Delete</button>
                <button name="copyBtn" class="btn btn-success"  float-end ">Copy</button>
            </form>
        </div>
    </div>
    <?php endif;?>

    <?php endforeach;?>
    <?php endif;?>
    
</body>
</html>