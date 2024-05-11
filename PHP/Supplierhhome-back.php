<div>
  <a class="home" href="../SupplierWelcome.html"><i class='bx bxs-home-smile'></i>Home</a>
  <?php
    if (isset($_SERVER['HTTP_REFERER'])) {
      echo '<a class="left-back" href="' . $_SERVER['HTTP_REFERER'] . '"><i class="bx bxs-caret-left-circle"></i>Back</a>';
    } else {
      echo '<a class="left-back" href="../SupplierWelcome.html"><i class="bx bxs-caret-left-circle"></i>Back</a>';
    }
  ?>
</div>
