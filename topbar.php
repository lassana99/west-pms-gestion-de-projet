<!-- Navbar -->
<!-- Définition de la couleur de la barre au début -->
<nav style="background-color: #178d38;" class="main-header navbar navbar-expand navbar-primary navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <?php if(isset($_SESSION['login_id'])): ?>
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <?php endif; ?>
        <li>
            <!-- Définition du nom Projects Management System au titre -->
            <a class="nav-link text-white" href="./" role="button">
                <large><h1 style="font-size: 1.5em;">WEST Ingénierie Projects Management System</h1></large>
            </a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" aria-expanded="true" href="javascript:void(0)">
                <span>
                    <div class="d-flex badge-pill align-items-center">
                        <!-- Afficher la photo de l'utilisateur s'il y en a une, sinon les initiales -->
                        <?php
                        // Chemin de l'image par défaut
                        $defaultAvatar = 'assets/uploads/icone.jpeg';
                        
                        // Chemin de l'image associée à l'utilisateur s'il en a une
                        $userAvatar = isset($_SESSION['login_picture']) && !empty($_SESSION['login_picture']) ? 'assets/uploads/' . $_SESSION['login_picture'] : '';

                        // Vérification si le fichier de l'utilisateur existe, sinon utiliser les initiales
                        if (!empty($userAvatar) && file_exists($userAvatar)) {
                            $pictureSrc = $userAvatar;
                            echo '<img src="' . $pictureSrc . '" alt="User Avatar" class="img-size-50 img-circle mr-2" style="width: 30px; height: 30px;">';
                        } else {
                            // Générer les initiales de l'utilisateur
                            $initials = strtoupper($_SESSION['login_firstname'][0] . $_SESSION['login_lastname'][0]);
                            echo '<div class="user-initials mr-2" style="width: 30px; height: 30px; background-color: blue; color: white; display: flex; align-items: center; justify-content: center; border-radius: 50%;">' . $initials . '</div>';
                        }
                        ?>
                        <span><b><?php echo ucwords($_SESSION['login_firstname'] . ' ' . $_SESSION['login_lastname']) ?></b></span>
                        <span class="fa fa-angle-down ml-2"></span>
                    </div>
                </span>
            </a>
            <div class="dropdown-menu" aria-labelledby="account_settings" style="left: -2.5em;">
                <a class="dropdown-item" href="javascript:void(0)" id="manage_account"><i class="fa fa-cog"></i> Gérer le compte</a>
                <a class="dropdown-item" href="ajax.php?action=logout"><i class="fa fa-power-off"></i> Déconnexion</a>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
<script>
    $('#manage_account').click(function(){
        uni_modal('Gérer le compte','manage_user.php?id=<?php echo $_SESSION['login_id'] ?>')
    })
</script>
