<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}
if(isset($_GET['id'])){
  $list_id = $_GET['id'];}

$userid = $_SESSION['user_id'];
// declare error array
$errors = array();


// delcare defaults
$title              = $_POST['title'] ?? "";
$description        = $_POST['description'] ?? "";
$status             = $_POST['status'] ?? "";
$details            = $_POST['details'] ?? "";
$proof              = $_FILES['proof'] ?? null;
$rating             = $_POST['rating'] ?? "50";
$comp_date          = $_POST['completionDate'] ?? "";
$public             = $_POST['public_view'] ?? 'Public';
$oldfile            = false;



//Include library and connect to DB
require './includes/library.php';

$pdo = connectDB();
$check = "SELECT user_id FROM 3420_assg_lists WHERE list_id = ?";
$checkownership = $pdo->prepare($check);
$checkownership->execute([$list_id]);
$result = $checkownership->fetch(PDO::FETCH_ASSOC);
if ($result['user_id'] != $userid){
  header("Location: index.php");
}
$getdata = "SELECT * FROM 3420_assg_lists WHERE list_id = ?";
$userdata= $pdo->prepare($getdata);
$userdata->execute([$list_id]);
$formdata = $userdata->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['submit'])) {
      
    // Sanitize all text inputs
    $title= htmlspecialchars($title);
    $description= htmlspecialchars($description);
    $details= htmlspecialchars($details);

 if (strlen($title) == 0) {
    $errors['title'] = true;
  }
  if (strlen($description) == 0) {
    $errors['description'] = true;
  }
  $valid_status = ["o", "p", "c"];
  if (!in_array($status, $valid_status)) {
    $errors['status'] = true;
  }
  if (strlen($details) === 0) {
    $errors['details'] = true;
  }
  if (isset($formdata['image_url']) && !isset($_FILES["proof"])){
    $oldfile = true;
  }
  else{
    $allowed_image_extension = array(
        "png",
        "jpg",
        "jpeg"
    );
    
    //Set Variables for File Upload
    $file_extension = pathinfo($_FILES["proof"]["name"], PATHINFO_EXTENSION);
    $maxsize    = 1500000;
    $path = WEBROOT."www_data/";
    $fileroot = "ListImage";
    
  if (!isset($formdata['image_url'])) //Removes the file check if there is already one in the directory!
  {

    // Validate file input to check if is not empty
    if (!file_exists($_FILES["proof"]["tmp_name"])) {
        $errors['proof'] = true;
    }    // Validate file input to check if is with valid extension
    else if (!in_array($file_extension, $allowed_image_extension)) {
        $errors['prooftype'] = true;
    }    // Validate image file size
    else if (($_FILES["proof"]["size"] >= $maxsize || ($_FILES["proof"]["size"] == 0))) {
        $errors['proofsize'] = true;
    }
    elseif(is_uploaded_file($_FILES["proof"]['tmp_name'])){
          //get the original file name for extension, where 'fileToProcess' was the name of the
          //file upload form element
          $filename = $_FILES["proof"]['name'];
          $exts = explode(".", $filename); // split based on period
          $ext = $exts[count($exts)-1]; //take the last split (contents after last period)
          $filename = $fileroot.$list_id.".".$ext;  //build new filename
          $newname = $path.$filename; //add path the file name

          // delete previous file in folder (as ones with different extensions would not be replaced)
          if(isset($formdata['image_url'])){
            $del_file = $path.$formdata['image_url'];
            array_map( "unlink", glob($del_file));
            }
          move_uploaded_file($_FILES['proof']['tmp_name'], $newname);
        }

  }
  else {
      //get the original file name for extension, where 'fileToProcess' was the name of the
      //file upload form element
      $filename = $_FILES["proof"]['name'];

      // Get the old extension
      $oldexts = explode(".", $formdata['image_url']);
      $oldext = $oldexts[count($oldexts)-1];

      $exts = explode(".", $filename); // split based on period
      $ext = $exts[count($exts)-1]; //take the last split (contents after last period)

      if (!empty($ext)){
      $filename = $fileroot.$list_id.".".$ext;  //build new filename
      $newname = $path.$filename; //add path to the file name
      echo "$filename";}
      else {
        $filename = $fileroot.$list_id.".".$oldext;  //build new filename
      $newname = $path.$filename; //add path to the file name
      echo "$filename";
      }

      if(is_uploaded_file($_FILES["proof"]['tmp_name'])){
      // delete previous file in folder (as ones with different extensions would not be replaced)
        $del_file = $path.$formdata['image_url'];
        array_map( "unlink", glob($del_file));
        move_uploaded_file($_FILES['proof']['tmp_name'], $newname);
      }
  }
}
    // If no errors, update database
    if (count($errors) === 0) {

    // Edit the list in Database`:
    if ($oldfile != true){
    if(empty($comp_date)){$comp_date = "0000:00:00";} // if statement to set a default for database
      $query = "UPDATE `3420_assg_lists` SET `title` = ?, `description` = ?, `status`= ?, `details`= ?, `image_url`= ?, `rating` = ?, `completion_date` = ?, `publicity` = ?
      WHERE `list_id` = ? AND `user_id` = ?";
      $edit_stmt = $pdo->prepare($query);
      $edit_stmt->execute([$title, $description, $status, $details, $filename, $rating, $comp_date, $public, $list_id, $userid]);}
      else{
        $query = "UPDATE `3420_assg_lists` SET `title` = ?, `description` = ?, `status`= ?, `details`= ?, `rating` = ?, `completion_date` = ?, `publicity` = ?
      WHERE `list_id` = ? AND `user_id` = ?";
      $edit_stmt = $pdo->prepare($query);
      $edit_stmt->execute([$title, $description, $status, $details, $rating, $comp_date, $public, $list_id, $userid]);
    }

   // Redirect:
   header("Location: edited.php?id=<?php echo $list_id; ?>");
    exit;
    }
  }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script
      src="https://kit.fontawesome.com/05ad49203b.js"
      crossorigin="anonymous"
    ></script>
    <title>Edit Item</title>
    <!-- include javascript and css-->
    <link rel="stylesheet" href="styles/main.css">
    <script defer src="js/scripts.js"></script>
  </head>
  <body>
    <header>
      <!--This will be the main heading of the page so users know what page they're on-->
      <h1>Edit Bucket List Items</h1>

      <?php include './includes/nav.php' ?>
    </header>
    <form id="edit-form" method="post" action="" enctype="multipart/form-data">
      <fieldset>
        <legend>List Info</legend>
        <div>
          <label for="title">Title:</label>
          <input type="text" id="title" name="title" value="<?php echo $formdata["title"]; ?>">
          <span class="error <?= !isset($errors['title']) ? 'hidden' : '' ?>">></span>
        </div>
        <div>
        <label for="description">Description:</label>
          <textarea id="description" name="description" value="<?= $description ?>"><?php echo $formdata["description"]; ?></textarea>
          <span class="error <?= !isset($errors['description']) ? 'hidden' : '' ?>"></span>
        </div>
      </fieldset>
      <fieldset>
        <legend>Status</legend>
        <div>
          <input type="radio" name="status" id="onhold" value="o"
          <?php if(isset($formdata["status"])){if($formdata["status"] == "o") echo 'checked';} ?>>
          <label for="onhold">On Hold</label>
        </div>
        <div>
          <input type="radio" name="status" id="progressing" value="p"
          <?php if(isset($formdata["status"])){if($formdata["status"] == "p") echo 'checked';} ?>>
          <label for="progressing">In Progress</label>
        </div>
        <div>
          <input type="radio" name="status" id="complete" value="c"
          <?php if(isset($formdata["status"])){if($formdata["status"] == "c") echo 'checked';} ?>>
          <label for="complete">Completed</label>
        </div>
        <span class="error <?= !isset($errors['status']) ? 'hidden' : '' ?>">Please Choose List Status.</span>
      </fieldset>

      <fieldset>
        <legend>Validation</legend>
        <div>
          <label for="details">Details:</label>
          <textarea id="details" name="details" value="<?= $details ?>"><?php echo $formdata["details"]; ?></textarea>
          <span class="error <?= !isset($errors['details']) ? 'hidden' : '' ?>">Please Describe Your Entry.</span>
        </div>
        <div>
          <label for="proof">Proof (Image upload):</label>
          <input type="file" id="proof" name="proof">
          <span class="error <?= !isset($errors['proof']) ? 'hidden' : '' ?>">Please Upload Your File.</span>
          <span class="error <?= !isset($errors['prooferror']) ? 'hidden' : '' ?>">Something Went Wrong With The Image.</span>
          <span class="error <?= !isset($errors['proofsize']) ? 'hidden' : '' ?>">The File Is Too Large (Max 1.5MB).</span>
          <span class="error <?= !isset($errors['prooftype']) ? 'hidden' : '' ?>">The File Is The Wrong Format (PNG, JPG, JPEG).</span>
        
          <?php 
          $printpath = "/~$direx[2]/www_data/";
          if (isset($formdata['image_url'])) {?>
            <div><?php echo "Current File on The List:"; ?></div>
             <div>
              <img src="<?php echo $printpath . $formdata['image_url']?>" height="300">
             </div>
          		<?php } ?>
        </div>

      </fieldset>
      <fieldset>
        <legend>Completed</legend>
        <div>
          <label for="rating">Score:</label>
          <input type="range" id="rating" name="rating" min="1" max="100" value="<?php if(isset($formdata["rating"])){echo $formdata["rating"];} ?>">
          <output for="rating"></output>
          <?php echo $formdata["rating"]?>
        </div>
        <div>
          <label for="completionDate">Completion Date:</label>
          <input type="date" id="completionDate" name="completionDate" value="<?php if(isset($formdata["completion_date"])){echo $formdata["completion_date"];} ?>">
        </div>
      </fieldset>
      <fieldset>
        <legend>Options</legend>
      <div>
          <label for="public_view">Make List Public?:</label>
          <input type="hidden" id="public_view" name="public_view" value="Private"<?php if($formdata["status"] == 'Private') echo 'checked'; ?>>
          <input type="checkbox" id="public_view" name="public_view" value="Public"<?php if($formdata["status"] == 'Public') echo 'checked'; ?>>
        </div>
      </fieldset>
      <button id="submit" name="submit">Update</button>
    </form>
  </main>
  <?php include './includes/footer.php' ?>
</body>

</html>