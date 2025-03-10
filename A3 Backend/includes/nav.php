<nav>
    <ul>
        <li><a href="./index.php"><i class="fa-solid fa-house"></i></a></li>
        <li><a href="./list.php"><i class="fa-solid fa-clipboard-list"></i></a></li>
        <?php
        if (isset($_SESSION['username'])) {
            // Display user-specific links when logged in
            echo '<li><a href="./logout.php"><i class="fa-solid fa-right-from-bracket"></i></a></li>';
            echo '<li><a href="./edit-account.php"><i class="fa-solid fa-user-edit"></i></a></li>';
            echo '<li><a href="./delete-account.php"><i class="fa-solid fa-user-times"></i></a></li>';
        } else {
            // Display login and register links when logged out
            echo '<li><a href="./login.php"><i class="fa-solid fa-right-to-bracket"></i></a></li>';
            echo '<li><a href="./register.php"><i class="fa-solid fa-user-plus"></i></a></li>';
        }
        ?>
        <li><a href="./search.php"><i class="fa-solid fa-magnifying-glass"></i></a></li>
    </ul>
</nav>
