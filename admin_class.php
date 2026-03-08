<?php
session_start();
ini_set('display_errors', 1);

class Action {
    private $db;

    public function __construct() {
        ob_start();
        include 'db_connect.php';
        $this->db = $conn;
    }

    function __destruct() {
        $this->db->close();
        ob_end_flush();
    }

    function login() {
        extract($_POST);
        $email = $this->db->real_escape_string($email);
        $password = $this->db->real_escape_string($password);
        $qry = $this->db->query("SELECT *, concat(firstname, ' ', lastname) as name FROM users WHERE email = '$email' AND password = '".md5($password)."'");
        if ($qry->num_rows > 0) {
            foreach ($qry->fetch_array() as $key => $value) {
                if ($key != 'password' && !is_numeric($key))
                    $_SESSION['login_'.$key] = $value;
            }
            return 1;
        } else {
            return 2;
        }
    }

    function logout() {
        session_destroy();
        foreach ($_SESSION as $key => $value) {
            unset($_SESSION[$key]);
        }
        header("location:login.php");
    }

    function save_user() {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id', 'cpass', 'password')) && !is_numeric($k)) {
                $v = $this->db->real_escape_string($v);
                if (empty($data)) {
                    $data .= " $k='$v' ";
                } else {
                    $data .= ", $k='$v' ";
                }
            }
        }
        if (!empty($password)) {
            $data .= ", password=md5('$password') ";
        }
        $check = $this->db->query("SELECT * FROM users WHERE email = '$email' ".(!empty($id) ? " AND id != $id" : ''))->num_rows;
        if ($check > 0) {
            return 2;
            exit;
        }
        if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
            $fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
            $move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/'.$fname);
            $data .= ", picture = '$fname' ";
        }
        if (empty($id)) {
            $save = $this->db->query("INSERT INTO users SET $data");
        } else {
            $save = $this->db->query("UPDATE users SET $data WHERE id = $id");
        }
        if ($save) {
            return 1;
        }
    }

    function signup() {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id', 'cpass')) && !is_numeric($k)) {
                $v = $this->db->real_escape_string($v);
                if ($k == 'password') {
                    if (empty($v)) continue;
                    $v = md5($v);
                }
                if (empty($data)) {
                    $data .= " $k='$v' ";
                } else {
                    $data .= ", $k='$v' ";
                }
            }
        }

        $check = $this->db->query("SELECT * FROM users WHERE email = '$email' ".(!empty($id) ? " AND id != $id" : ''))->num_rows;
        if ($check > 0) {
            return 2;
            exit;
        }
        if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
            $fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
            $move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/'.$fname);
            $data .= ", picture = '$fname' ";
        }
        if (empty($id)) {
            $save = $this->db->query("INSERT INTO users SET $data");
        } else {
            $save = $this->db->query("UPDATE users SET $data WHERE id = $id");
        }
        if ($save) {
            if (empty($id)) $id = $this->db->insert_id;
            foreach ($_POST as $key => $value) {
                if (!in_array($key, array('id', 'cpass', 'password')) && !is_numeric($key))
                    $_SESSION['login_'.$key] = $value;
            }
            $_SESSION['login_id'] = $id;
            if (isset($_FILES['img']) && !empty($_FILES['img']['tmp_name']))
                $_SESSION['login_picture'] = $fname;
            return 1;
        }
    }

    function update_user() {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id', 'cpass', 'table', 'password')) && !is_numeric($k)) {
                $v = $this->db->real_escape_string($v);
                if (empty($data)) {
                    $data .= " $k='$v' ";
                } else {
                    $data .= ", $k='$v' ";
                }
            }
        }
        $check = $this->db->query("SELECT * FROM users WHERE email = '$email' ".(!empty($id) ? " AND id != $id" : ''))->num_rows;
        if ($check > 0) {
            return 2;
            exit;
        }
        if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
            $fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
            $move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/'.$fname);
            $data .= ", picture = '$fname' ";
        }
        if (!empty($password))
            $data .= " ,password=md5('$password') ";
        if (empty($id)) {
            $save = $this->db->query("INSERT INTO users SET $data");
        } else {
            $save = $this->db->query("UPDATE users SET $data WHERE id = $id");
        }
        if ($save) {
            foreach ($_POST as $key => $value) {
                if ($key != 'password' && !is_numeric($key))
                    $_SESSION['login_'.$key] = $value;
            }
            if (isset($_FILES['img']) && !empty($_FILES['img']['tmp_name']))
                $_SESSION['login_picture'] = $fname;
            return 1;
        }
    }

    function delete_user() {
        extract($_POST);
        $delete = $this->db->query("DELETE FROM users WHERE id = $id");
        if ($delete)
            return 1;
    }

    function save_system_settings() {
        extract($_POST);
        $data = '';
        foreach ($_POST as $k => $v) {
            if (!is_numeric($k)) {
                $v = $this->db->real_escape_string($v);
                if (empty($data)) {
                    $data .= " $k='$v' ";
                } else {
                    $data .= ", $k='$v' ";
                }
            }
        }
        if ($_FILES['cover']['tmp_name'] != '') {
            $fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['cover']['name'];
            $move = move_uploaded_file($_FILES['cover']['tmp_name'], '../assets/uploads/'.$fname);
            $data .= ", cover_img = '$fname' ";
        }
        $chk = $this->db->query("SELECT * FROM system_settings");
        if ($chk->num_rows > 0) {
            $save = $this->db->query("UPDATE system_settings SET $data WHERE id =".$chk->fetch_array()['id']);
        } else {
            $save = $this->db->query("INSERT INTO system_settings SET $data");
        }
        if ($save) {
            foreach ($_POST as $k => $v) {
                if (!is_numeric($k)) {
                    $_SESSION['system'][$k] = $v;
                }
            }
            if ($_FILES['cover']['tmp_name'] != '') {
                $_SESSION['system']['cover_img'] = $fname;
            }
            return 1;
        }
    }

    function save_image() {
        extract($_FILES['file']);
        if (!empty($tmp_name)) {
            $fname = strtotime(date('Y-m-d H:i')).'_'.str_replace(" ", "-", $name);
            $move = move_uploaded_file($tmp_name, 'assets/uploads/'. $fname);
            if ($move) {
                return json_encode(array('link' => 'assets/uploads/'.$fname));
            }
        }
    }

    function save_project() {
        extract($_POST);
        $data = "";
    
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id', 'user_id')) && !is_numeric($k)) {
                $v = $this->db->real_escape_string($v);
                $data .= empty($data) ? "$k='$v'" : ", $k='$v'";
            }
        }
    
        if (isset($user_id)) {
            $data .= ", user_id='" . implode(',', $user_id) . "' ";
        }
    
        if (empty($id)) {
            // Nouveau projet
            $table = (isset($status) && $status == 5) ? "project_archive_list" : "project_list";
            $save = $this->db->query("INSERT INTO $table SET $data");
        } else {
            if (isset($status) && $status == 5) {
                // Déplacement vers la table archive
                $get = $this->db->query("SELECT * FROM project_list WHERE id = $id");
                if ($get->num_rows > 0) {
                    $project = $get->fetch_assoc();
                    $archive_data = "";
                    foreach ($project as $k => $v) {
                        if ($k != 'id') {
                            $v = $this->db->real_escape_string($v);
                            $archive_data .= empty($archive_data) ? "$k='$v'" : ", $k='$v'";
                        }
                    }
                    $save = $this->db->query("INSERT INTO project_archive_list SET $archive_data");
                    if ($save) {
                        $this->db->query("DELETE FROM project_list WHERE id = $id");
                    }
                } else {
                    return 0;
                }
            } else {
                // Mise à jour normale
                $save = $this->db->query("UPDATE project_list SET $data WHERE id = $id");
            }
        }
    
        return $save ? 1 : 0;
    }
    

    

    function delete_project() {
        extract($_POST);
        $delete = $this->db->query("DELETE FROM project_list WHERE id = ".$id);
        if ($delete) {
            return 1;
        }
    }

    function save_project_archive() {
    extract($_POST);
    $data = "";

    // Déterminer la table cible en fonction de "status"
    $table = "project_list"; // Par défaut
    if (isset($status)) {
        if ($status == 5) {
            $table = "project_archive_list"; // Projet terminé
        } elseif ($status == 0 || $status == 3) {
            $table = "project_list"; // Projet en attente ou en cours
        } else {
            return 0; // État invalide
        }
    }

    // Préparer les données pour insertion/mise à jour
    foreach ($_POST as $k => $v) {
        if (!in_array($k, array('id', 'user_id')) && !is_numeric($k)) {
            $v = $this->db->real_escape_string($v);
            if (empty($data)) {
                $data .= " $k='$v' ";
            } else {
                $data .= ", $k='$v' ";
            }
        }
    }

    // Gestion de la colonne "user_id" pour les utilisateurs multiples
    if (isset($user_id)) {
        $data .= ", user_id='" . implode(',', $user_id) . "' ";
    }

    // Si on enregistre dans les archives, forcer le statut à 5 (Fait)
    if ($table == "project_archive_list") {
        $data .= ", status='5' ";
    }

    // Insérer ou mettre à jour dans la table cible
    if (empty($id)) {
        $save = $this->db->query("INSERT INTO $table SET $data");
    } else {
        $save = $this->db->query("UPDATE $table SET $data WHERE id = $id");
    }

    if ($save) {
        return 1;
    } else {
        return 0;
    }
}

    function delete_project_archive() {
        extract($_POST);
        $delete = $this->db->query("DELETE FROM project_archive_list WHERE id = ".$id);
        if ($delete) {
            return 1;
        }
    }

    // For the doc

    function save_contract() {
        // Extraction des variables POST
        extract($_POST);
    
        // Initialisation des variables
        $data = "";
        $fileName = "";
    
        // Limites de taille du fichier (en octets)
        $maxFileSize = 10 * 1024 * 1024; // 10 Mo, à ajuster selon les besoins
    
        // Traitement du fichier téléchargé
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['file'];
            $fileName = basename($file['name']);
            $fileTmpName = $file['tmp_name'];
            $uploadDir = 'assets/docs/'; // Répertoire de destination pour les fichiers
            $fileDestination = $uploadDir . $fileName;
    
            // Vérifie la taille du fichier
            if ($file['size'] > $maxFileSize) {
                echo "Le fichier est trop volumineux. La taille maximale autorisée est de " . ($maxFileSize / 1024 / 1024) . " Mo.";
                return 0; // Échec de la sauvegarde en raison de la taille du fichier
            }
    
            // Déplace le fichier vers le répertoire de destination
            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                // Ajoute le nom du fichier à la variable $data
                if (empty($data)) {
                    $data .= "file_name='$fileName'";
                } else {
                    $data .= ", file_name='$fileName'";
                }
            } else {
                echo "Erreur lors du déplacement du fichier.";
                return 0; // Erreur lors du déplacement du fichier
            }
        } else if (isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
            echo "Erreur de téléchargement du fichier : " . $_FILES['file']['error'];
            return 0; // Erreur lors du téléchargement du fichier
        }
    
        // Prépare les autres données pour la base de données
        foreach ($_POST as $k => $v) {
            if ($k === 'recipient_id' && is_array($v)) {
                // Convertit le tableau en une chaîne séparée par des virgules
                $v = implode(',', array_map(function($item) {
                    return strip_tags($this->db->real_escape_string($item));
                }, $v));
            } else if (!in_array($k, array('contract_id')) && !is_numeric($k)) {
                $v = strip_tags($this->db->real_escape_string($v)); // Supprime les balises HTML et échappe les chaînes
            }
    
            if (!empty($v)) {
                if (empty($data)) {
                    $data .= "$k='$v'";
                } else {
                    $data .= ", $k='$v'";
                }
            }
        }
    
        // Insérer ou mettre à jour le contract dans la base de données
        if (empty($contract_id)) {
            $query = "INSERT INTO contract SET $data";
            $save = $this->db->query($query);
            if ($save) {
                $contract_id = $this->db->insert_id;
            } else {
                echo "Erreur SQL lors de l'insertion : " . $this->db->error;
                return 0;
            }
        } else {
            $query = "UPDATE contract SET $data WHERE contract_id = $contract_id";
            $save = $this->db->query($query);
            if (!$save) {
                echo "Erreur SQL lors de la mise à jour : " . $this->db->error;
                return 0;
            }
        }
    
        // Vérifie si la sauvegarde a réussi
        if ($save) {
            return 1; // Succès
        } else {
            echo "Erreur inconnue lors de l'enregistrement des données";
            return 0; // Échec
        }
    }
    
    
    
    
    function delete_contract() {
        extract($_POST);
        $delete = $this->db->query("DELETE FROM contract WHERE contract_id = $id");
        if ($delete) {
            return 1;
        }
    }

    // Fonction pour sauvegarder une facture
    function save_invoice() {
        extract($_POST);

        $data = "";
        $fileName = "";
        $maxFileSize = 10 * 1024 * 1024; // 10 Mo
        $uploadDir = 'assets/factures/';

        // Traitement du fichier
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['file'];
            $fileName = basename($file['name']);
            $fileTmpName = $file['tmp_name'];
            $fileDestination = $uploadDir . $fileName;

            if ($file['size'] > $maxFileSize) {
                echo "Le fichier est trop volumineux. Taille maximale : " . ($maxFileSize / 1024 / 1024) . " Mo.";
                return 0;
            }

            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                $data .= "file_name='$fileName'";
            } else {
                echo "Erreur lors du déplacement du fichier.";
                return 0;
            }
        } else if (isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
            echo "Erreur de téléchargement du fichier : " . $_FILES['file']['error'];
            return 0;
        }

        // Préparation des autres champs
        $fields = ['name', 'project_name', 'mission_id', 'description', 'file_name'];

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = $this->db->real_escape_string(strip_tags($_POST[$field]));

                if (!empty($value)) {
                    if (!empty($data)) {
                        $data .= ", ";
                    }
                    $data .= "$field='$value'";
                }
            }
        }

        // date_updated = automatique dans la base

        // INSERT ou UPDATE
        if (empty($id)) {
            $query = "INSERT INTO invoice_list SET $data";
            $save = $this->db->query($query);
            if ($save) {
                $id = $this->db->insert_id;
            } else {
                echo "Erreur SQL lors de l'insertion : " . $this->db->error;
                return 0;
            }
        } else {
            $query = "UPDATE invoice_list SET $data WHERE id = $id";
            $save = $this->db->query($query);
            if (!$save) {
                echo "Erreur SQL lors de la mise à jour : " . $this->db->error;
                return 0;
            }
        }

        return $save ? 1 : 0;
    }

    // Fonction pour supprimer une facture
    function delete_invoice() {
        extract($_POST);

        if (empty($id)) {
            echo "ID de facture manquant.";
            return 0;
        }

        $delete = $this->db->query("DELETE FROM invoice_list WHERE id = $id");

        if ($delete) {
            return 1;
        } else {
            echo "Erreur SQL lors de la suppression : " . $this->db->error;
            return 0;
        }
    }


    // Fonction pour sauvegarder un attachement
    function save_attachment() {
        extract($_POST);

        $data = "";
        $fileName = "";
        $maxFileSize = 10 * 1024 * 1024; // 10 Mo
        $uploadDir = 'assets/attachements/';

        // Traitement du fichier
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['file'];
            $fileName = basename($file['name']);
            $fileTmpName = $file['tmp_name'];
            $fileDestination = $uploadDir . $fileName;

            if ($file['size'] > $maxFileSize) {
                echo "Le fichier est trop volumineux. Taille maximale : " . ($maxFileSize / 1024 / 1024) . " Mo.";
                return 0;
            }

            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                $data .= "file_name='$fileName'";
            } else {
                echo "Erreur lors du déplacement du fichier.";
                return 0;
            }
        } else if (isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
            echo "Erreur de téléchargement du fichier : " . $_FILES['file']['error'];
            return 0;
        }

        // Préparation des autres champs
        $fields = ['name', 'project_name', 'mission_id', 'description', 'file_name'];

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = $this->db->real_escape_string(strip_tags($_POST[$field]));

                if (!empty($value)) {
                    if (!empty($data)) {
                        $data .= ", ";
                    }
                    $data .= "$field='$value'";
                }
            }
        }

        // date_updated = automatique dans la base

        // INSERT ou UPDATE
        if (empty($id)) {
            $query = "INSERT INTO attachment_list SET $data";
            $save = $this->db->query($query);
            if ($save) {
                $id = $this->db->insert_id;
            } else {
                echo "Erreur SQL lors de l'insertion : " . $this->db->error;
                return 0;
            }
        } else {
            $query = "UPDATE attachment_list SET $data WHERE id = $id";
            $save = $this->db->query($query);
            if (!$save) {
                echo "Erreur SQL lors de la mise à jour : " . $this->db->error;
                return 0;
            }
        }

        return $save ? 1 : 0;
    }

    // Fonction pour supprimer un attachement
    function delete_attachment() {
        extract($_POST);

        if (empty($id)) {
            echo "ID de l'attachement manquant.";
            return 0;
        }

        $delete = $this->db->query("DELETE FROM attachment_list WHERE id = $id");

        if ($delete) {
            return 1;
        } else {
            echo "Erreur SQL lors de la suppression : " . $this->db->error;
            return 0;
        }
    }

    // Fonction pour sauvegarder un décompte
    function save_statement() {
        extract($_POST);

        $data = "";
        $fileName = "";
        $maxFileSize = 10 * 1024 * 1024; // 10 Mo
        $uploadDir = 'assets/decomptes/';

        // Traitement du fichier
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['file'];
            $fileName = basename($file['name']);
            $fileTmpName = $file['tmp_name'];
            $fileDestination = $uploadDir . $fileName;

            if ($file['size'] > $maxFileSize) {
                echo "Le fichier est trop volumineux. Taille maximale : " . ($maxFileSize / 1024 / 1024) . " Mo.";
                return 0;
            }

            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                $data .= "file_name='$fileName'";
            } else {
                echo "Erreur lors du déplacement du fichier.";
                return 0;
            }
        } else if (isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
            echo "Erreur de téléchargement du fichier : " . $_FILES['file']['error'];
            return 0;
        }

        // Préparation des autres champs
        $fields = ['name', 'project_name', 'mission_id', 'description', 'file_name'];

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = $this->db->real_escape_string(strip_tags($_POST[$field]));

                if (!empty($value)) {
                    if (!empty($data)) {
                        $data .= ", ";
                    }
                    $data .= "$field='$value'";
                }
            }
        }

        // date_updated = automatique dans la base

        // INSERT ou UPDATE
        if (empty($id)) {
            $query = "INSERT INTO statement_list SET $data";
            $save = $this->db->query($query);
            if ($save) {
                $id = $this->db->insert_id;
            } else {
                echo "Erreur SQL lors de l'insertion : " . $this->db->error;
                return 0;
            }
        } else {
            $query = "UPDATE statement_list SET $data WHERE id = $id";
            $save = $this->db->query($query);
            if (!$save) {
                echo "Erreur SQL lors de la mise à jour : " . $this->db->error;
                return 0;
            }
        }

        return $save ? 1 : 0;
    }

    // Fonction pour supprimer un attachement
    function delete_statement() {
        extract($_POST);

        if (empty($id)) {
            echo "ID de décompte manquant.";
            return 0;
        }

        $delete = $this->db->query("DELETE FROM statement_list WHERE id = $id");

        if ($delete) {
            return 1;
        } else {
            echo "Erreur SQL lors de la suppression : " . $this->db->error;
            return 0;
        }
    }

    // Fonction pour sauvegarder un document iso de terrain
    function save_iso_terrain() {
        extract($_POST);
    
        $data = "";
        $fileName = "";
        $maxFileSize = 10 * 1024 * 1024; // 10 Mo
        $uploadDir = 'assets/iso_terrain/';
    
        // Traitement du fichier
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['file'];
            $fileName = basename($file['name']);
            $fileTmpName = $file['tmp_name'];
            $fileDestination = $uploadDir . $fileName;
    
            if ($file['size'] > $maxFileSize) {
                echo "Le fichier est trop volumineux. Taille maximale : " . ($maxFileSize / 1024 / 1024) . " Mo.";
                return 0;
            }
    
            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                $data .= "file_name='{$this->db->real_escape_string($fileName)}'";
            } else {
                echo "Erreur lors du déplacement du fichier.";
                return 0;
            }
        } else if (isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
            echo "Erreur de téléchargement du fichier : " . $_FILES['file']['error'];
            return 0;
        }
    
        // Préparation des autres champs
        if (isset($project_name)) {
            $project_name = $this->db->real_escape_string(strip_tags($project_name));
            $data .= (!empty($data) ? ", " : "") . "project_name='$project_name'";
        }
    
        if (isset($mission_id)) {
            $mission_id = (int) $mission_id;
            $data .= (!empty($data) ? ", " : "") . "mission_id=$mission_id";
        }
    
        if (isset($user_name)) {
            $user_name = $this->db->real_escape_string(strip_tags($user_name));
            $data .= (!empty($data) ? ", " : "") . "user_name='$user_name'";
        }
    
        if (isset($description)) { // ici corrigé
            $description = $this->db->real_escape_string(strip_tags($description));
            $data .= (!empty($data) ? ", " : "") . "description='$description'";
        }
    
        // INSERT ou UPDATE
        if (empty($id)) {
            $query = "INSERT INTO iso_terrain_list SET $data";
            $save = $this->db->query($query);
            if ($save) {
                $id = $this->db->insert_id;
            } else {
                echo "Erreur SQL lors de l'insertion : " . $this->db->error;
                return 0;
            }
        } else {
            $query = "UPDATE iso_terrain_list SET $data WHERE id = " . (int)$id;
            $save = $this->db->query($query);
            if (!$save) {
                echo "Erreur SQL lors de la mise à jour : " . $this->db->error;
                return 0;
            }
        }
    
        return $save ? 1 : 0;
    }
    

    // Fonction pour supprimer un document iso de terrain
    function delete_iso_terrain() {
        extract($_POST);

        if (empty($id)) {
            echo "ID du document manquant.";
            return 0;
        }

        $id = (int) $id;
        $delete = $this->db->query("DELETE FROM iso_terrain_list WHERE id = $id");

        if ($delete) {
            return 1;
        } else {
            echo "Erreur SQL lors de la suppression : " . $this->db->error;
            return 0;
        }
    }

    // Fonction pour sauvegarder un document iso de bureau
    function save_iso_bureau() {
        extract($_POST);

        $data = "";
        $fileName = "";
        $maxFileSize = 10 * 1024 * 1024; // 10 Mo
        $uploadDir = 'assets/iso_bureau/';

        // Traitement du fichier
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['file'];
            $fileName = basename($file['name']);
            $fileTmpName = $file['tmp_name'];
            $fileDestination = $uploadDir . $fileName;

            if ($file['size'] > $maxFileSize) {
                echo "Le fichier est trop volumineux. Taille maximale : " . ($maxFileSize / 1024 / 1024) . " Mo.";
                return 0;
            }

            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                $data .= "file_name='" . $this->db->real_escape_string($fileName) . "'";
            } else {
                echo "Erreur lors du déplacement du fichier.";
                return 0;
            }
        } else if (isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
            echo "Erreur de téléchargement du fichier : " . $_FILES['file']['error'];
            return 0;
        }

        // Préparation des autres champs
        $fields = ['attached_name', 'user_name', 'description'];

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = $this->db->real_escape_string(strip_tags($_POST[$field]));
                if (!empty($value)) {
                    if (!empty($data)) {
                        $data .= ", ";
                    }
                    $data .= "$field='$value'";
                }
            }
        }

        // INSERT ou UPDATE
        if (empty($id)) {
            $query = "INSERT INTO iso_bureau_list SET $data";
            $save = $this->db->query($query);
            if ($save) {
                $id = $this->db->insert_id;
            } else {
                echo "Erreur SQL lors de l'insertion : " . $this->db->error;
                return 0;
            }
        } else {
            $query = "UPDATE iso_bureau_list SET $data WHERE id = " . intval($id);
            $save = $this->db->query($query);
            if (!$save) {
                echo "Erreur SQL lors de la mise à jour : " . $this->db->error;
                return 0;
            }
        }

        return $save ? 1 : 0;
    }

    // Fonction pour supprimer un document iso de bureau
    function delete_iso_bureau() {
        extract($_POST);

        if (empty($id)) {
            echo "ID du document manquant.";
            return 0;
        }

        $id = intval($id);
        $delete = $this->db->query("DELETE FROM iso_bureau_list WHERE id = $id");

        if ($delete) {
            return 1;
        } else {
            echo "Erreur SQL lors de la suppression : " . $this->db->error;
            return 0;
        }
    }


        // Fonction pour sauvegarder un CV Batiment
        function save_cv_batiment() {
            extract($_POST);
        
            $data = "";
            $fileName = "";
            $maxFileSize = 10 * 1024 * 1024; // 10 Mo
            $uploadDir = 'assets/cv_batiment/';
        
            // Traitement du fichier
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['file'];
                $fileName = basename($file['name']);
                $fileTmpName = $file['tmp_name'];
                $fileDestination = $uploadDir . $fileName;
        
                if ($file['size'] > $maxFileSize) {
                    echo "Le fichier est trop volumineux. Taille maximale : " . ($maxFileSize / 1024 / 1024) . " Mo.";
                    return 0;
                }
        
                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    $data .= "file_name='" . $this->db->real_escape_string($fileName) . "'";
                } else {
                    echo "Erreur lors du déplacement du fichier.";
                    return 0;
                }
            } else if (isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
                echo "Erreur de téléchargement du fichier : " . $_FILES['file']['error'];
                return 0;
            }
        
            // Gestion de la spécialité : si "Autre" est sélectionné, prendre le champ saisi
            if (isset($_POST['speciality'])) {
                $speciality_value = $_POST['speciality'];
        
                if ($speciality_value == 'Autre' && !empty($_POST['speciality_other'])) {
                    $speciality_value = $_POST['speciality_other'];
                }
        
                $speciality_value = $this->db->real_escape_string(strip_tags($speciality_value));
        
                if (!empty($speciality_value)) {
                    if (!empty($data)) {
                        $data .= ", ";
                    }
                    $data .= "speciality='$speciality_value'";
                }
            }
        
            // Préparation des autres champs
            $fields = ['user_name', 'year_experience', 'description'];
        
            foreach ($fields as $field) {
                if (isset($_POST[$field])) {
                    $value = $this->db->real_escape_string(strip_tags($_POST[$field]));
                    if (!empty($value)) {
                        if (!empty($data)) {
                            $data .= ", ";
                        }
                        $data .= "$field='$value'";
                    }
                }
            }
        
            // Insertion ou mise à jour
            if (empty($id)) {
                $query = "INSERT INTO cv_batiment_list SET $data, date_created = CURRENT_TIMESTAMP, date_updated = CURRENT_TIMESTAMP";
                $save = $this->db->query($query);
                if ($save) {
                    $id = $this->db->insert_id;
                } else {
                    echo "Erreur SQL lors de l'insertion : " . $this->db->error;
                    return 0;
                }
            } else {
                $query = "UPDATE cv_batiment_list SET $data, date_updated = CURRENT_TIMESTAMP WHERE id = " . intval($id);
                $save = $this->db->query($query);
                if (!$save) {
                    echo "Erreur SQL lors de la mise à jour : " . $this->db->error;
                    return 0;
                }
            }
        
            return $save ? 1 : 0;
        }
        

    // Fonction pour supprimer un CV batiment
    function delete_cv_batiment() {
        extract($_POST);

        if (empty($id)) {
            echo "ID de CV manquant.";
            return 0;
        }

        $id = intval($id);
        $delete = $this->db->query("DELETE FROM cv_batiment_list WHERE id = $id");

        if ($delete) {
            return 1;
        } else {
            echo "Erreur SQL lors de la suppression : " . $this->db->error;
            return 0;
        }
    }


    
        // Fonction pour sauvegarder un CV Route
        function save_cv_route() {
            extract($_POST);
        
            $data = "";
            $fileName = "";
            $maxFileSize = 10 * 1024 * 1024; // 10 Mo
            $uploadDir = 'assets/cv_route/';
        
            // Traitement du fichier
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['file'];
                $fileName = basename($file['name']);
                $fileTmpName = $file['tmp_name'];
                $fileDestination = $uploadDir . $fileName;
        
                if ($file['size'] > $maxFileSize) {
                    echo "Le fichier est trop volumineux. Taille maximale : " . ($maxFileSize / 1024 / 1024) . " Mo.";
                    return 0;
                }
        
                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    $data .= "file_name='" . $this->db->real_escape_string($fileName) . "'";
                } else {
                    echo "Erreur lors du déplacement du fichier.";
                    return 0;
                }
            } else if (isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
                echo "Erreur de téléchargement du fichier : " . $_FILES['file']['error'];
                return 0;
            }
        
            // Gérer le champ "speciality" correctement
            if (isset($speciality)) {
                if ($speciality == 'Autre' && isset($speciality_other) && !empty(trim($speciality_other))) {
                    // Si Autre est sélectionné et une spécialité est précisée
                    $speciality = $speciality_other;
                }
                $speciality = $this->db->real_escape_string(strip_tags($speciality));
                if (!empty($data)) {
                    $data .= ", ";
                }
                $data .= "speciality='$speciality'";
            }
        
            // Gérer les autres champs
            $fields = ['user_name', 'year_experience', 'description']; // on retire "speciality" ici
        
            foreach ($fields as $field) {
                if (isset($_POST[$field])) {
                    $value = $this->db->real_escape_string(strip_tags($_POST[$field]));
                    if (!empty($value)) {
                        if (!empty($data)) {
                            $data .= ", ";
                        }
                        $data .= "$field='$value'";
                    }
                }
            }
        
            // Si pas d'ID, insertion (INSERT), sinon mise à jour (UPDATE)
            if (empty($id)) {
                $query = "INSERT INTO cv_route_list SET $data, date_created = CURRENT_TIMESTAMP, date_updated = CURRENT_TIMESTAMP";
                $save = $this->db->query($query);
                if ($save) {
                    $id = $this->db->insert_id;
                } else {
                    echo "Erreur SQL lors de l'insertion : " . $this->db->error;
                    return 0;
                }
            } else {
                $query = "UPDATE cv_route_list SET $data, date_updated = CURRENT_TIMESTAMP WHERE id = " . intval($id);
                $save = $this->db->query($query);
                if (!$save) {
                    echo "Erreur SQL lors de la mise à jour : " . $this->db->error;
                    return 0;
                }
            }
        
            return $save ? 1 : 0;
        }
        
        // Fonction pour supprimer un CV route
        function delete_cv_route() {
            extract($_POST);
    
            if (empty($id)) {
                echo "ID de CV manquant.";
                return 0;
            }
    
            $id = intval($id);
            $delete = $this->db->query("DELETE FROM cv_route_list WHERE id = $id");
    
            if ($delete) {
                return 1;
            } else {
                echo "Erreur SQL lors de la suppression : " . $this->db->error;
                return 0;
            }
        }

        
        // Fonction pour sauvegarder un CV Amenagement
        function save_cv_amenagement() {
            extract($_POST);
        
            $data = "";
            $fileName = "";
            $maxFileSize = 10 * 1024 * 1024; // 10 Mo
            $uploadDir = 'assets/cv_amenagement/';
        
            // Traitement du fichier
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['file'];
                $fileName = basename($file['name']);
                $fileTmpName = $file['tmp_name'];
                $fileDestination = $uploadDir . $fileName;
        
                if ($file['size'] > $maxFileSize) {
                    echo "Le fichier est trop volumineux. Taille maximale : " . ($maxFileSize / 1024 / 1024) . " Mo.";
                    return 0;
                }
        
                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    $data .= "file_name='" . $this->db->real_escape_string($fileName) . "'";
                } else {
                    echo "Erreur lors du déplacement du fichier.";
                    return 0;
                }
            } else if (isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
                echo "Erreur de téléchargement du fichier : " . $_FILES['file']['error'];
                return 0;
            }
        
            // Gérer le champ "speciality" correctement
            if (isset($speciality)) {
                if ($speciality == 'Autre' && isset($speciality_other) && !empty(trim($speciality_other))) {
                    // Si Autre est sélectionné et une spécialité est précisée
                    $speciality = $speciality_other;
                }
                $speciality = $this->db->real_escape_string(strip_tags($speciality));
                if (!empty($data)) {
                    $data .= ", ";
                }
                $data .= "speciality='$speciality'";
            }
        
            // Gérer les autres champs
            $fields = ['user_name', 'year_experience', 'description']; // on retire "speciality" ici
        
            foreach ($fields as $field) {
                if (isset($_POST[$field])) {
                    $value = $this->db->real_escape_string(strip_tags($_POST[$field]));
                    if (!empty($value)) {
                        if (!empty($data)) {
                            $data .= ", ";
                        }
                        $data .= "$field='$value'";
                    }
                }
            }
        
            // Si pas d'ID, insertion (INSERT), sinon mise à jour (UPDATE)
            if (empty($id)) {
                $query = "INSERT INTO cv_amenagement_list SET $data, date_created = CURRENT_TIMESTAMP, date_updated = CURRENT_TIMESTAMP";
                $save = $this->db->query($query);
                if ($save) {
                    $id = $this->db->insert_id;
                } else {
                    echo "Erreur SQL lors de l'insertion : " . $this->db->error;
                    return 0;
                }
            } else {
                $query = "UPDATE cv_amenagement_list SET $data, date_updated = CURRENT_TIMESTAMP WHERE id = " . intval($id);
                $save = $this->db->query($query);
                if (!$save) {
                    echo "Erreur SQL lors de la mise à jour : " . $this->db->error;
                    return 0;
                }
            }
        
            return $save ? 1 : 0;
        }
        
        // Fonction pour supprimer un CV Amenagement
        function delete_cv_amenagement() {
            extract($_POST);
    
            if (empty($id)) {
                echo "ID de CV manquant.";
                return 0;
            }
    
            $id = intval($id);
            $delete = $this->db->query("DELETE FROM cv_amenagement_list WHERE id = $id");
    
            if ($delete) {
                return 1;
            } else {
                echo "Erreur SQL lors de la suppression : " . $this->db->error;
                return 0;
            }
        }

        // Fonction pour sauvegarder un CV AEP
        function save_cv_aep() {
            extract($_POST);
        
            $data = "";
            $fileName = "";
            $maxFileSize = 10 * 1024 * 1024; // 10 Mo
            $uploadDir = 'assets/cv_aep/';
        
            // Traitement du fichier
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['file'];
                $fileName = basename($file['name']);
                $fileTmpName = $file['tmp_name'];
                $fileDestination = $uploadDir . $fileName;
        
                if ($file['size'] > $maxFileSize) {
                    echo "Le fichier est trop volumineux. Taille maximale : " . ($maxFileSize / 1024 / 1024) . " Mo.";
                    return 0;
                }
        
                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    $data .= "file_name='" . $this->db->real_escape_string($fileName) . "'";
                } else {
                    echo "Erreur lors du déplacement du fichier.";
                    return 0;
                }
            } else if (isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
                echo "Erreur de téléchargement du fichier : " . $_FILES['file']['error'];
                return 0;
            }
        
            // Gérer le champ "speciality" correctement
            if (isset($speciality)) {
                if ($speciality == 'Autre' && isset($speciality_other) && !empty(trim($speciality_other))) {
                    // Si Autre est sélectionné et une spécialité est précisée
                    $speciality = $speciality_other;
                }
                $speciality = $this->db->real_escape_string(strip_tags($speciality));
                if (!empty($data)) {
                    $data .= ", ";
                }
                $data .= "speciality='$speciality'";
            }
        
            // Gérer les autres champs
            $fields = ['user_name', 'year_experience', 'description']; // on retire "speciality" ici
        
            foreach ($fields as $field) {
                if (isset($_POST[$field])) {
                    $value = $this->db->real_escape_string(strip_tags($_POST[$field]));
                    if (!empty($value)) {
                        if (!empty($data)) {
                            $data .= ", ";
                        }
                        $data .= "$field='$value'";
                    }
                }
            }
        
            // Si pas d'ID, insertion (INSERT), sinon mise à jour (UPDATE)
            if (empty($id)) {
                $query = "INSERT INTO cv_aep_list SET $data, date_created = CURRENT_TIMESTAMP, date_updated = CURRENT_TIMESTAMP";
                $save = $this->db->query($query);
                if ($save) {
                    $id = $this->db->insert_id;
                } else {
                    echo "Erreur SQL lors de l'insertion : " . $this->db->error;
                    return 0;
                }
            } else {
                $query = "UPDATE cv_aep_list SET $data, date_updated = CURRENT_TIMESTAMP WHERE id = " . intval($id);
                $save = $this->db->query($query);
                if (!$save) {
                    echo "Erreur SQL lors de la mise à jour : " . $this->db->error;
                    return 0;
                }
            }
        
            return $save ? 1 : 0;
        }
        
        // Fonction pour supprimer un CV AEP
        function delete_cv_aep() {
            extract($_POST);
    
            if (empty($id)) {
                echo "ID de CV manquant.";
                return 0;
            }
    
            $id = intval($id);
            $delete = $this->db->query("DELETE FROM cv_aep_list WHERE id = $id");
    
            if ($delete) {
                return 1;
            } else {
                echo "Erreur SQL lors de la suppression : " . $this->db->error;
                return 0;
            }
        }



    function save_report() {
        // Extraction des variables POST
        extract($_POST);
    
        // Initialisation des variables
        $data = "";
        $fileName = "";
        $table = "report"; // Par défaut, la table cible est "report"
    
        // Vérification si "date_validation" est renseigné
        if (!empty($date_validation)) {
            $table = "report_archive"; // Changer la table cible en "report_archive"
        }
    
        // Traitement du fichier téléchargé
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['file'];
            $fileName = basename($file['name']);
            $fileTmpName = $file['tmp_name'];
            $uploadDir = ($table === "report") ? 'assets/report_file/' : 'assets/report_archive_file/'; // Répertoire selon la table
            $fileDestination = $uploadDir . $fileName;
    
            // Déplace le fichier vers le répertoire de destination
            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                // Ajoute le nom du fichier à la variable $data
                $data .= "report_file='$fileName', ";
            } else {
                echo "Erreur lors du déplacement du fichier";
                return 0; // Erreur lors du déplacement du fichier
            }
        } else if (isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
            echo "Erreur de téléchargement du fichier : " . $_FILES['file']['error'];
            return 0; // Erreur lors du téléchargement du fichier
        }
    
        // Prépare les autres données pour la base de données
        foreach ($_POST as $k => $v) {
            if ($k === 'recipient_id' && is_array($v)) {
                // Convertit le tableau en une chaîne séparée par des virgules
                $v = implode(',', array_map(function ($item) {
                    return strip_tags($this->db->real_escape_string($item));
                }, $v));
            } else if (!in_array($k, array('report_id')) && !is_numeric($k)) {
                $v = strip_tags($this->db->real_escape_string($v)); // Strip HTML tags and escape string
            }
    
            if (!empty($v)) {
                $data .= "$k='$v', ";
            }
        }
    
        $data = rtrim($data, ", "); // Supprime la virgule et l'espace à la fin
    
        // Insérer ou mettre à jour les données dans la table appropriée
        if (empty($report_id)) {
            $query = "INSERT INTO $table SET $data";
            $save = $this->db->query($query);
            if ($save) {
                $report_id = $this->db->insert_id;
            } else {
                echo "Erreur SQL lors de l'insertion : " . $this->db->error;
                return 0;
            }
        } else {
            $query = "UPDATE $table SET $data WHERE report_id = $report_id";
            $save = $this->db->query($query);
            if (!$save) {
                echo "Erreur SQL lors de la mise à jour : " . $this->db->error;
                return 0;
            }
        }
    
        // Vérifie si la sauvegarde a réussi
        if ($save) {
            return 1; // Succès
        } else {
            echo "Erreur inconnue lors de l'enregistrement des données";
            return 0; // Échec
        }
    }
    

    
    function delete_report() {
        extract($_POST);
        $delete = $this->db->query("DELETE FROM report WHERE report_id = $id");
        if ($delete) {
            return 1;
        }
    }

    //Définition des fonction de report_archive
    function save_report_archive() {
        extract($_POST); // Extraction des données POST
        $data = ""; // Initialisation des données pour la requête
        $fileName = "";
        
        // Gestion du fichier téléchargé
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['file'];
            $fileName = strtotime("now") . "_" . basename($file['name']);
            $uploadDir = 'assets/report_archive_file/'; // Répertoire cible
            $fileDestination = $uploadDir . $fileName;
        
            // Déplace le fichier vers le répertoire cible
            if (move_uploaded_file($file['tmp_name'], $fileDestination)) {
                $data .= "`report_file` = '$fileName', ";
            } else {
                echo "Erreur lors du téléchargement du fichier.";
                return 0; // Erreur lors du téléchargement
            }
        } else if (isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
            echo "Erreur de téléchargement du fichier : " . $_FILES['file']['error'];
            return 0; // Autre erreur de téléchargement
        }
        
        // Construction des données pour la requête SQL
        foreach ($_POST as $k => $v) {
            if ($k === 'recipient_id' && is_array($v)) {
                // Convertit un tableau en chaîne séparée par des virgules
                $v = implode(',', array_map(function ($item) {
                    return $this->db->real_escape_string(strip_tags($item));
                }, $v));
            } else {
                $v = $this->db->real_escape_string(strip_tags($v)); // Nettoyage des données
            }
        
            if (!in_array($k, ['report_id', 'file']) && !empty($v)) {
                $data .= "`$k` = '$v', ";
            }
        }
        
        $data = rtrim($data, ', '); // Retire la dernière virgule et l'espace
        
        // Si la date de validation est renseignée, déplacer le rapport dans `report_archive` et supprimer de `report`
        if (!empty($date_validation)) {
            // Insérer ou mettre à jour dans la table `report_archive`
            if (empty($report_id)) {
                $query = "INSERT INTO `report_archive` SET $data";
                $save = $this->db->query($query);
                if ($save) {
                    $report_id = $this->db->insert_id;
    
                    // Supprimer le rapport de la table `report`
                    $delete_query = "DELETE FROM `report` WHERE `report_id` = $report_id";
                    $this->db->query($delete_query);
    
                    return 1; // Succès
                } else {
                    echo "Erreur SQL lors de l'insertion dans `report_archive` : " . $this->db->error;
                    return 0; // Échec
                }
            } else {
                // Si le rapport existe, mettre à jour dans `report_archive`
                $query = "UPDATE `report_archive` SET $data WHERE `report_id` = $report_id";
                $save = $this->db->query($query);
                if ($save) {
                    // Supprimer le rapport de la table `report`
                    $delete_query = "DELETE FROM `report` WHERE `report_id` = $report_id";
                    $this->db->query($delete_query);
    
                    return 1; // Succès
                } else {
                    echo "Erreur SQL lors de la mise à jour dans `report_archive` : " . $this->db->error;
                    return 0; // Échec
                }
            }
        } else {
            // Si la date de validation n'est pas renseignée, simplement mettre à jour sans déplacer
            if (empty($report_id)) {
                $query = "INSERT INTO `report` SET $data";
                $save = $this->db->query($query);
                if ($save) {
                    $report_id = $this->db->insert_id;
                    return 1; // Succès
                } else {
                    echo "Erreur SQL lors de l'insertion dans `report` : " . $this->db->error;
                    return 0; // Échec
                }
            } else {
                $query = "UPDATE `report` SET $data WHERE `report_id` = $report_id";
                $save = $this->db->query($query);
                if ($save) {
                    return 1; // Succès
                } else {
                    echo "Erreur SQL lors de la mise à jour dans `report` : " . $this->db->error;
                    return 0; // Échec
                }
            }
        }
    }
    





    function delete_report_archive() {
        extract($_POST); // Extraction des données POST
        $delete = $this->db->query("DELETE FROM `report_archive` WHERE `report_id` = $id");
        if ($delete) {
            return 1; // Succès
        } else {
            echo "Erreur SQL lors de la suppression : " . $this->db->error;
            return 0; // Échec
        }
    }
    
    

    function save_activity(){
        extract($_POST);
        $conn = $this->db; // Connexion à la base de données
    
        // Récupérer l'ID de l'utilisateur depuis la session
        $user_id = isset($_SESSION['login_id']) ? mysqli_real_escape_string($conn, $_SESSION['login_id']) : '';
    
        // Préparer la date de création
        $date_created = date('Y-m-d H:i:s');
    
        // Initialiser la variable pour le nom du fichier
        $activity_file = '';
    
        // Si une ID est fournie, nous allons vérifier s'il existe un fichier actuellement
        if (!empty($id)) {
            // Obtenir le fichier actuel de la tâche (si présent)
            $result = $conn->query("SELECT activity_file FROM activity_list WHERE id = '$id'");
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $activity_file = $row['activity_file']; // Conserver le fichier actuel
            }
        }
    
        // Gestion de l'upload du fichier
        if (isset($_FILES['activity_file']) && $_FILES['activity_file']['error'] == UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['activity_file']['tmp_name'];
            $file_name = basename($_FILES['activity_file']['name']);
            $file_dest = 'assets/activity_file/' . $file_name;
    
            // Déplacer le fichier vers le répertoire de destination
            if (move_uploaded_file($file_tmp, $file_dest)) {
                $activity_file = $file_name; // Stocker le nom du fichier
            } else {
                echo "Erreur lors du téléchargement du fichier.";
                return 0;
            }
        }
    
        if (empty($id)) {
            // Insertion d'une nouvelle tâche
            $query = "INSERT INTO activity_list (user_id, project_id, activity, description, date_created, activity_file) 
                      VALUES ('$user_id', '$project_id', '$activity', '" . mysqli_real_escape_string($conn, htmlentities($description, ENT_QUOTES)) . "', '$date_created', '$activity_file')";
        } else {
            // Mise à jour d'une tâche existante
            $query = "UPDATE activity_list SET project_id = '$project_id', activity = '$activity', description = '" . mysqli_real_escape_string($conn, htmlentities($description, ENT_QUOTES)) . "', activity_file = '$activity_file' WHERE id = '$id'";
        }
    
        $save = $conn->query($query);
    
        if ($save) {
            return 1;
        } else {
            echo "Erreur : " . $conn->error;
            return 0;
        }
    }

    function delete_activity() {
        extract($_POST);
    
        // Sécuriser l'ID pour éviter les injections SQL
        $id = intval($id);
    
        // Vérifier si l'ID est valide
        if ($id > 0) {
            $delete = $this->db->query("DELETE FROM activity_list WHERE id = $id");
    
            // Vérifier si la suppression a réussi
            if ($delete) {
                return 1;
            } else {
                // En cas d'échec, retourner 0 ou un message d'erreur
                return 0;
            }
        } else {
            // Si l'ID n'est pas valide, retourner 0 ou un message d'erreur
            return 0;
        }
    }
    
    function get_report() {
        extract($_POST);
        $data = array();
        $qry = $this->db->query("SELECT p.*, concat(u.lastname, ', ', u.firstname, ' ', u.middlename) as uname, t.activity, t.start_date, t.end_date FROM user_productivity p INNER JOIN activity_list t ON t.id = p.activity_id INNER JOIN users u ON u.id = p.user_id WHERE p.id = $id ");
        if ($qry) {
            foreach ($qry->fetch_array() as $k => $v) {
                $data[$k] = $v;
            }
            return json_encode($data);
        }
    }
}
?>
