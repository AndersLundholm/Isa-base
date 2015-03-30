<?php
/**
* User authentication.
*
*/
class CUser {

    private $db = null;

    public function __construct($db) {
        $this->db = $db;
    }


    /**
    * Creates a password hash and inserts the user information in the database.
    * @param string $user the user acronym.
    * @param string $password the unhashed password.
    * @param string $name the users full name (optional).
    */
    public function createUser($user, $password, $name=null) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO RMUser (acronym, password, name) VALUES (?, ?, ?)";
        $res = $this->db->ExecuteQuery($sql, array($user, $hash, $name));

        if($res) {
            $output = "Kontot skapades. ";
            $output .= $this->login($user, $password);
        } else {
            $output = "Det gick inte att skapa kontot.";
        }

        return $output;
    }



    /**
    * Change password for a user.
    * @param string $user the user acronym.
    * @param string $oldpw the old password.
    * @param string $oldpw the old password.
    * @param string $newpw the new password.
    * @param string $confirmpw the confirmation of the new password.
    */
    public function changePassword($user, $oldpw, $newpw, $confirmpw) {
        $result = null;
        $sql = "SELECT * FROM RMUser WHERE acronym = ?";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, array($user));

        if($res!=null && password_verify($oldpw, $res[0]->password)) {
            if($newpw == $confirmpw) {
                $hash = password_hash($newpw, PASSWORD_BCRYPT);
                $sql = "UPDATE RMUser SET password = ? WHERE acronym = ?";
                $res = $this->db->ExecuteQuery($sql, array($hash, $user));

                if($res) {
                    $result = true;
                } else {
                    $result = false;
                }
                
            } else {
                $result = false;
            }

        } else {
            $result = false;

        }

        return $result;
    }



    /**
    * Check if the passed password matches the password, for the passed user, 
    * that is hashed in the database. If passwords match the user information 
    * is saved in the session.
    * @param string $user the user acronym.
    * @param string $password the unhashed password.
    * @return string with user information.
    */
    public function login($user, $password) { 
        $output;      
        $sql = "SELECT * FROM RMUser WHERE acronym = ?";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, array($user));

        if(!$this->isAuthenticated() && $res!=null && password_verify($password, $res[0]->password)) {
            $_SESSION['user'] = $res[0];
            header('Location: status.php');
            die("Redirecting to status page.");

        } elseif($this->isAuthenticated()) {
            $output = "Användare " . $this->getAcronym() . 
            " är inloggad. Logga ut först för att logga in med en annan användare";

        } else {
            $output = "Fel användarnamn eller lösenord.";

        }
        return $output;
    }


    /**
    * Unsets the user information from the session and redirects user to status page.
    */
    public function logout() {
        unset($_SESSION['user']);
        header('Location: status.php');
        die("Redirecting to status page.");
    }


    /**
    * Check if the passed password matches the password, for the passed user, 
    * that is hashed in the database. If passwords match the user information 
    * is saved in the session.
    * @return string with user information.
    */
    public function getStatus() {
        $status;
        if($this->isAuthenticated()) {
            $status = "Du är inloggad som: " . $_SESSION['user']->acronym;
        } else {
            $status = "Du är utloggad.";
        }
        return $status;
    }



    /**
    * Check if user is authenticated.
    * @return true if user is authenticated, otherwise false.
    */
    public function isAuthenticated() {
        return isset($_SESSION['user']) ? true : false;
    }



    /**
    * Check if user is administrator.
    * @return true if user is logged in as admin, otherwise false.
    */
    public function isAdmin() {
        return ($_SESSION['user']->acronym == 'admin') ? true : false;
    }



    /**
    * Check if user is authenticated, if not the user is redirected to login 
    * page and script stops executing.
    */
    public function checkAuthenticated() {
        if(!$_SESSION['user']) {
            header('Location: login.php');
            die("Redirecting to login page.");
        } 
    }



    /**
    * Return acronym.
    * @return string with logged in users acronym.
    */
    public function getAcronym() {
        return isset($_SESSION['user']->acronym) ? $_SESSION['user']->acronym : null;
    }


    /**
    * Return name.
    * @return string with logged in users name.
    */
    public function getName() {
        return isset($_SESSION['user']->name) ? $_SESSION['user']->name : null;
    }   


    /**
    * Return a title string login/logout depending if the user is authenticated.
    * @return string with login/logout string.
    */
    public function getStatusString() {
        $str;
        if($this->isAuthenticated()) {
            $str = "Logga ut";
        } else {
            $str = "Logga in";
        }
        return $str;
    } 


    /**
    * Return a link to login page if user is not logged in, otherwise returns 
    * link to logout page.
    * @return string with login/logout link.
    */
    public function getLink() {
        $link;
        if($this->isAuthenticated()) {
            $link = "<a href='logout.php'>Logga ut</a>";
        } else {
            $link = "<a href='login.php'>Logga in</a>";
        }
        return $link;
    } 



    /**
    * Fetches all profiles and returns them as HTML.
    * @return string with all profiles as HTML.
    */
    public function getAllProfiles() {
        $html = null;

        if($this->isAuthenticated()) {
            $html = "<p><a href='editprofile.php?user=" . $this->getAcronym() .
                    "'>Redigera din profil</a></p>";
        } else {
            $html = "<p><a href='signup.php'>Skapa en profil</a></p>";
        }

        $html .= "<table>";
        
        $sql = "SELECT acronym, name FROM RMUser";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql);
        if($res) {
            foreach ($res as $val) {
                $html .= "<tr><td>Namn: " . $val->name . 
                         "</tr><tr></td><td>Användarnamn: " . 
                         $val->acronym . "</tr><tr><td>&nbsp;</td></tr>";
            }
        }
        $html .= "</table>";

        return $html;
    } 


    /**
    * Update the session.
    * @return string with user information if the update was successful or not.
    */
    public function updateSession($user) {

        $sql = "SELECT * FROM RMUser WHERE acronym = ?";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, array($user));

        if($res) {
            $_SESSION['user'] = $res[0];
        }
    }

    /**
    * Update user profile.
    * @return string with user information if the update was successful or not.
    */
    public function updateProfile() {
        $this->checkAuthenticated();
        $current = isset($_SESSION['user']) ? $_SESSION['user'] : null;

        $authenticate = $this->getAcronym();
        $user = isset($_GET['user'])   ? strip_tags($_GET['user']) : null;

        $name = isset($_POST['name'])  ? strip_tags($_POST['name']) : null;
        $acronym = isset($_POST['acronym'])  ? strip_tags($_POST['acronym']) : null;
        $oldpw = isset($_POST['oldpw'])  ? strip_tags($_POST['oldpw']) : null;
        $newpw = isset($_POST['newpw'])  ? strip_tags($_POST['newpw']) : null;
        $confirmpw = isset($_POST['confirmpw'])  ? strip_tags($_POST['confirmpw']) : null;
        $save = isset($_POST['saveProfile'])  ? true : false;
        $output = null;


        
        // Check if form was submitted
        $output = null;
        if($save && $authenticate == $user) {
            if($newpw) {
                $output .= $this->changePassword($user, $oldpw, $newpw, $confirmpw) ? "Lösenordet uppdaterades. " : "Du har angivit fel lösenord, lösenordet uppdaterades inte. ";
            }

            if($name != $current->name || $acronym != $current->acronym) {
                $val = null;
                $val .= isset($name) ? "name = '{$name}'," : null;
                $val .= isset($name) ? "acronym = '{$acronym}'," : null;
                $val = rtrim($val, ",");
                $sql = "UPDATE RMUser SET {$val} WHERE acronym = '{$user}';";

                $res = $this->db->ExecuteQuery($sql, array($val, $user));
                if($res) {
                    $output .= 'Användarinformationen sparades.';
                    $this->updateSession($user);


                    // header('Location: profiles.php');
                    // die("Redirecting to profiles page.");
                }
                else {
                    $output .= 'Informationen sparades EJ.<br><pre>' . print_r($this->db->ErrorInfo(), 1) . '</pre>';
                }

            }
            
        } else if(!$authenticate == $user) {
            $output .= "Du kan endast ändra din egen profil.";
        }

        return $output;
    }


    /**
    * @return HTML login form.
    */
    public function getLoginForm() {
        $form = "
        <form class='form' method='post'>
            <fieldset>
            <legend>Inloggning</legend>
            <ul>
                <li><label>Användare:<input type='text' name='acronym' value='' autofocus required></label></li>
                <li><label>Lösenord:<input type='password' name='password' value='' required></label></li>
                <li><input type='submit' name='login' value='Login'></li>
            </ul>
            </fieldset>
        </form>";
        return $form;
    } 



    /**
    * @return HTML signup form.
    */
    public function getSignupForm() {
        $form = "
        <form class='form' method='post'>
            <fieldset>
            <legend>Skapa ett konto</legend>
            <ul>
                <li><label>Namn:<input type='text' name='name' value='' autofocus></label></li>
                <li><label>Användarnamn:<input type='text' name='acronym' value='' required></label></li>
                <li><label>Lösenord:<input type='password' name='password' value='' required></label></li>
                <li><input type='submit' name='signup' value='Skapa kontot'></li>
            </ul>
            </fieldset>
        </form>";
        return $form;
    } 



    /**
    * @return HTML form for editing the profile.
    */
    public function getEditProfileForm() {
        $content = isset($_SESSION['user']) ? $_SESSION['user'] : null;
        $form = "
        <form class='form' method='post'>
            <fieldset>
            <legend>Uppdatera kontot</legend>
            <ul>
                <li><label>Namn:<input type='text' name='name' value='$content->name' autofocus></label></li>
                <li><label>Användarnamn:<input type='text' name='acronym' value='$content->acronym' required></label></li>
                <li>&nbsp;</li>
                <fieldset>
                <legend>Byt lösenord</legend>
                    <li><label>Gammalt lösenord:<input type='password' name='oldpw' value=''></label></li>
                    <li><label>Nytt lösenord:<input type='password' name='newpw' value=''></label></li>
                    <li><label>Bekräfta nytt lösenord:<input type='password' name='confirmpw' value=''></label></li>
                </fieldset>
                <li><input type='submit' name='saveProfile' value='Uppdatera kontot'></li>
            </ul>
            </fieldset>
        </form>";
        return $form;
    } 


}