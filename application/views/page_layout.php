<!DOCTYPE html>
<html lang="en">

	
	<?php
     $this->load->view('general/headtag');
     ?>

	<body >
		     <?php
        // $this->load->view('general/header');

	?>

<?php 
    //side bar function
      if (isset($page_layout)){
       $this->view($page_layout);
      }
    ?>

<?php

       // $this->load->view('general/footer');
        $this->load->view('general/foottag');
      
 
?>

</body>
</html>


