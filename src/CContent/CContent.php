<?php
/**
* CRUD Class.
*
*/
class CContent {

    protected $db = null;
    private $user = null;

    public function __construct($db) {
        $this->db = $db;
        $this->user = new CUser($db);
    }


    /**
    * Initiates/resets the database tables.
    */
    public function initDatabase() {
        $this->user->checkAuthenticated();

        $output = null;

        $sql = "
        DROP TABLE IF EXISTS RMContent;
        CREATE TABLE RMContent
        (
          id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
          author VARCHAR(12) NOT NULL,
          slug CHAR(80) UNIQUE,
          url CHAR(80) UNIQUE,
         
          type CHAR(80),
          title VARCHAR(80),
          data TEXT,
          filter CHAR(80),
          
          published DATETIME,
          created DATETIME,
          updated DATETIME,
          deleted DATETIME,

          FOREIGN KEY (author) REFERENCES RMUser (acronym)
         
        ) ENGINE INNODB CHARACTER SET utf8;";
        
        if($this->user->isAdmin()) {
            $res = $this->db->ExecuteQuery($sql);

            if($res) {
                $output = "Databasen återställd";
                header('Location: admin.php');
                die("Redirecting to view page.");
            } else {
                $output = 'Databasen återställdes EJ.<br><pre>' . print_r($this->db->ErrorInfo(), 1) . '</pre>';
            }
        } else {
                $output = 'Informationen sparades EJ.<br><pre>' . print_r($this->db->ErrorInfo(), 1) . '</pre>';
        }

        return $output;
    }


    /**
    * Populates the database with example data
    * @return string with user information if the operation was sucessful or not.
    */
    public function populateDatabase() {
        $this->user->checkAuthenticated();

        $populate   = isset($_POST['reset'])  ? true : false;
        $output = null;

        $sql = "INSERT INTO RMContent (author, slug, url, type, title, data, filter, published, created) VALUES
            ('admin', 'hem', 'hem', 'page', 'Hem', 'Detta är min hemsida. Den är skriven i [url=http://en.wikipedia.org/wiki/BBCode]bbcode[/url] vilket innebär att man kan formattera texten till [b]bold[/b] och [i]kursiv stil[/i] samt hantera länkar.\n\nDessutom finns ett filter \"nl2br\" som lägger in <br>-element istället för \\n, det är smidigt, man kan skriva texten precis som man tänker sig att den skall visas, med radbrytningar.', 'bbcode,nl2br', NOW(), NOW()),
            ('admin', 'om', 'om', 'page', 'Om', 'Detta är en sida om mig och min webbplats. Den är skriven i [Markdown](http://en.wikipedia.org/wiki/Markdown). Markdown innebär att du får bra kontroll över innehållet i din sida, du kan formattera och sätta rubriker, men du behöver inte bry dig om HTML.\n\nRubrik nivå 2\n-------------\n\nDu skriver enkla styrtecken för att formattera texten som **fetstil** och *kursiv*. Det finns ett speciellt sätt att länka, skapa tabeller och så vidare.\n\n###Rubrik nivå 3\n\nNär man skriver i markdown så blir det läsbart även som textfil och det är lite av tanken med markdown.', 'markdown', NOW(), NOW()),
            ('admin', 'blogpost-1', NULL, 'post', 'Välkommen till min blogg!', 'Detta är en bloggpost.\n\nNär det finns länkar till andra webbplatser så kommer de länkarna att bli klickbara.\n\nhttp://dbwebb.se är ett exempel på en länk som blir klickbar.', 'clickable,nl2br', NOW(), NOW()),
            ('admin', 'blogpost-2', NULL, 'post', 'Nu har sommaren kommit', 'Detta är en bloggpost som berättar att sommaren har kommit, ett budskap som kräver en bloggpost.', 'nl2br', NOW(), NOW()),
            ('admin', 'blogpost-3', NULL, 'post', 'Nu har hösten kommit', 'Detta är en bloggpost som berättar att sommaren har kommit, ett budskap som kräver en bloggpost', 'nl2br', NOW(), NOW())
            ;";
        if($this->user->isAdmin()) {
            $res = $this->db->ExecuteQuery($sql);

            if($res) {
                $output = "Databasen populerad med exempeldata";
                header('Location: admin.php');
                die("Redirecting to view page.");
            } else {
                $output = 'Databasen populerades EJ.<br><pre>' . print_r($this->db->ErrorInfo(), 1) . '</pre>';
            }
        } else {
                $output = 'Informationen sparades EJ.<br><pre>' . print_r($this->db->ErrorInfo(), 1) . '</pre>';
        }

        return $output;

    }


    /**
    * Create a link to the content, based on its type.
    * @param object $content to link to.
    * @return string with url to display content.
    */
    private function getUrlToContent($content) {
        $type = null;
        switch($content->type) {
            case 'page': $type = "page.php?url={$content->url}"; 
                break;
            case 'post': $type = "news.php?slug={$content->slug}"; 
                break;
        }

        return $type;
    }


    /**
    * Fetches all content.
    * @return string with a HTML list of all content.
    */
    public function getAllContent() {

        $sql = "SELECT *, (published <= NOW()) AS available
            FROM RMContent
            WHERE deleted IS NULL
            ORDER BY id DESC;";

        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql);

        $items = null;
        foreach($res AS $key => $val) {
            $items .= "<li>{$val->type} (" . (!$val->available ? 'inte ' : null) . 
            "publicerad): " . htmlentities($val->title, null, 'UTF-8') . 
            " (<a href='edit.php?id={$val->id}'>editera</a> <a href='" . 
            $this->getUrlToContent($val) . "'>visa</a> <a href='delete.php?type=post&id={$val->id}'>ta bort</a>)</li>\n";
        }

        $list = "<ul>";
        $list .= $items;
        $list .= "</ul>";

        return $list;
    }


    /**
    * Fetches a single post. 
    * @param string $id the id of the post.
    * @return object with single post.
    */
    public function getContent() {
        $id = isset($_GET['id']) ? strip_tags($_GET['id']) : null;

        $sql = "SELECT *, (published <= NOW()) AS available
            FROM RMContent
            WHERE id = ?";

        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, array($id));

        return $res[0];
    }



    /**
    * Fetches all categories.
    * @return string with a HTML list of all categories.
    */
    public function getAllCategories() {

        $sql = "SELECT DISTINCT category FROM RMContent;";

        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql);

        $items = null;
        foreach($res AS $key => $val) {
            $items .= "<li><a href='news.php?category={$val->category}'>" . htmlentities($val->category, null, 'UTF-8') . "</a></li>\n";
        }

        $html = "<h2>Kategorier</h2>\n<ul>";
        $html .= "<li><a href='news.php'>--Alla kategorier--</a></li>\n";
        $html .= $items;
        $html .= "</ul>";

        return $html;
    }



    /**
    * Add new content to database
    * @return string with user information if the operation was sucessful or not.
    */
    public function addContent() {
        $this->user->checkAuthenticated();

        $id     = isset($_POST['id'])    ? strip_tags($_POST['id']) : (isset($_GET['id']) ? strip_tags($_GET['id']) : null);
        $url    = isset($_POST['url'])   ? strip_tags($_POST['url']) : null;
        $data   = isset($_POST['data'])  ? $_POST['data'] : array();
        $category   = isset($_POST['category']) ? $_POST['category'] : null;
        $type   = isset($_POST['type'])  ? ($_POST['type'] == '' ? 'post' : strip_tags($_POST['type'])) : 'post';
        $filter = isset($_POST['filter']) ? $_POST['filter'] : array();
        $published = isset($_POST['published'])  ? strip_tags($_POST['published']) : null;
        $save   = isset($_POST['savePost'])  ? true : false;
        $acronym = $this->user->getAcronym();
        $output = null;

        if (isset($_POST['title'])){
            $title = strip_tags($_POST['title']);
            $slug = $this->slugify($title);
        } else {
            $title = null;
            $slug = null;
        }
        
        if($save && $this->user->isAdmin()) {
            $sql = '
                INSERT INTO RMContent (title, slug, url, data, category, type, filter, published, author, created)
                VALUES (?,?,?,?,?,?,?,?,?,NOW())';

            $published = empty($published) ? date("Y-m-d H:i:s") : $published; 
            $url = empty($url) ? null : $url;
            $params = array($title, $slug, $url, $data, $category, $type, $filter, $published, $acronym);
            $res = $this->db->ExecuteQuery($sql, $params);
            if($res) {
                $output = 'Informationen sparades.';
                header('Location: admin.php');
                die("Redirecting to admin page.");
            } else {
                $output = 'Informationen sparades EJ.<br><pre>' . print_r($this->db->ErrorInfo(), 1) . '</pre>';
            }
        } else if(!$this->user->isAdmin()) {
            $output = "Endast administratören kan ändra detta innehåll";
        }

        return $output;
    }


    /**
    * Delete content from database
    * @return string with user information if the operation was sucessful or not.
    */
    public function deleteContent() {
        $this->user->checkAuthenticated();

        $id = isset($_GET['id']) ? strip_tags($_GET['id']) : null;
        $outpu = null;

        $sql = "UPDATE RMContent SET deleted = NOW() WHERE id = $id";

        if($this->user->isAdmin()) {
            $res = $this->db->ExecuteQuery($sql);

            if($res) {
                $output = 'Posten togs bort.';
                header('Location: admin.php');
                die("Redirecting to view page.");
            } else {
                $output = 'Posten togs EJ bort.<br><pre>' . print_r($this->db->ErrorInfo(), 1) . '</pre>';
            }
        } else {
            $output = "Endast administratören kan ändra detta innehåll";
        }

        return $output;
    }


    /**
    * Update content.
    * @return string with user information if the update was successful or not.
    */
    public function updateContent() {
        $this->user->checkAuthenticated();

        $id     = isset($_POST['id'])    ? strip_tags($_POST['id']) : (isset($_GET['id']) ? strip_tags($_GET['id']) : null);
        $url    = isset($_POST['url'])   ? strip_tags($_POST['url']) : null;
        $data   = isset($_POST['data'])  ? $_POST['data'] : array();
        $category   = isset($_POST['category'])  ? $_POST['category'] : null;
        $type   = isset($_POST['type'])  ? strip_tags($_POST['type']) : array();
        $filter = isset($_POST['filter']) ? $_POST['filter'] : array();
        $published = isset($_POST['published'])  ? strip_tags($_POST['published']) : null;
        $save   = isset($_POST['savePost'])  ? true : false;
        $acronym = $this->user->getAcronym();

        if (isset($_POST['title'])){
            $title = strip_tags($_POST['title']);
            $slug = $this->slugify($title);
        } else {
            $title = null;
            $slug = null;
        }

        is_numeric($id) or die('Check: Id must be numeric.');

        // Check if form was submitted
        $output = null;
        if($save && $this->user->isAdmin()) {
            $sql = '
                UPDATE RMContent SET title = ?, slug = ?, url = ?, data = ?, 
                type = ?, filter = ?, published = ?, author = ?, updated = NOW()
                WHERE id = ?';
            $url = empty($url) ? null : $url;
            $params = array($title, $slug, $url, $data, $type, $filter, $published, $acronym, $id);
            $res = $this->db->ExecuteQuery($sql, $params);
            if($res) {
                $output = 'Informationen sparades.';
                header('Location: admin.php');
                die("Redirecting to admin page.");
            }
            else {
                $output = 'Informationen sparades EJ.<br><pre>' . print_r($this->db->ErrorInfo(), 1) . '</pre>';
            }
        } else if(!$this->user->isAdmin()) {
            $output = "Endast administratören kan ändra detta innehåll";
        }

        return $output;
    }


    /**
     * Create a slug of a string, to be used as url.
     * @param string $str the string to format as slug.
     * @returns str the formatted slug. 
     */
    function slugify($str) {
        $str = mb_strtolower(trim($str));
        $str = str_replace(array('å','ä','ö'), array('a','a','o'), $str);
        $str = preg_replace('/[^a-z0-9-]/', '-', $str);
        $str = trim(preg_replace('/-+/', '-', $str), '-');
        return $str;
    }


    /**
     * Create a breadcrumb of the query path.
     *
     * @param string $path to the current gallery directory.
     * @return string html with ul/li to display the thumbnail.
     */
    public function createBreadcrumb($dir, $home='?') {
      $parts = explode('/', trim(substr($dir, strlen($dir) + 1), '/'));
      $breadcrumb = "<ul class='breadcrumb'>\n<li><a href='{$home}'>Hem</a> »</li>\n";

      if(!empty($parts[0])) {
        $combine = null;
        foreach($parts as $part) {
          $combine .= ($combine ? '/' : null) . $part;
          $breadcrumb .= "<li><a href='?path={$combine}'>$part</a> » </li>\n";
        }
      }

      $breadcrumb .= "</ul>\n";
      return $breadcrumb;
    }



    /**
    * Create the form for adding and updating a post.
    * @param $content the content to fill in the form if there is any.
    * @param $str the titel of the form.
    * @param $form the type of form.
    * @return HTML update form.
    */
    public function getForm($content=null, $str=null, $form="post") {

        if($content == null) {
            $content = new stdClass();
            $content->id = '';
            $content->title = '';
            $content->slug = '';
            $content->url = '';
            $content->data = '';
            $content->category = '';
            $content->type = '';
            $content->filter = '';
            $content->published = '';
            $content->year = '';
            $content->price = '';
            $content->imdb = '';
            $content->youtube = '';
            $content->plot = '';
            $content->genres = '';
            $content->image = '';
        }

        if($form == "post"){
            $form = "
            <form class='form' method='post'>
                <fieldset>
                    <legend>{$str}</legend>
                    <input type='hidden' name='id' value='{$content->id}'>
                    <ul>
                        <li><label>Titel:<input type='text' name='title' value='{$content->title}' required></label></li>
                        <li><label>Slug:<input type='text' name='slug' value='{$content->slug}'></label></li>
                        <li><label>Url:<input type='text' name='url' value='{$content->url}'></label></li>
                        <li><label>Text:<textarea name='data'>{$content->data}</textarea></label></li>
                        <li><label>Kategori:<input type='text' name='category' value='{$content->category}'></label></li>
                        <li><label>Type:<input type='text' name='type' value='{$content->type}'></label></li>
                        <li><label>Filter:<input type='text' name='filter' value='{$content->filter}'></label></li>
                        <li><label>Publiseringsdatum:<input type='text' name='published' value='{$content->published}'></label></li>
                        <li><input type='submit' name='savePost' value='Spara'></li>
                        <li><a href='admin.php'>Visa alla</a></li>
                    </ul>   
                </fieldset>
            </form>";
        } else if($form == "reset"){
            $form = "
            <form class='form' method='post'>
                <fieldset>
                    <legend>{$str}</legend>
                    <ul>
                        <li><input type='reset' name='reset' value='Återställ'></li>
                    </ul>   
                </fieldset>
            </form>";
        }

        return $form;
    }

}