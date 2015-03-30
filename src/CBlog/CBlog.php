<?php
/**
* Creates a Blog.
*
*/
class CBlog extends CContent {

    private $slug = null;
    private $category = null;

    /**
    * Fetch the data from the database and return the complete page.
    * @param int $posts with the amount of posts to return.
    * @return string with the complete page in HTML.
    */
    public function getBlogContent($posts=10000) {
        $this->slug = isset($_GET['slug']) ? strip_tags($_GET['slug']) : null;
        $this->category = isset($_GET['category']) ? strip_tags($_GET['category']) : null;
        $params = array();
        $slugSql = null;
        $categorySql = null;
        $html = null;

        if($this->slug) {
            $slugSql = 'slug = ?';
            $params[] = $this->slug;
        } else {
            $slugSql = 1;
        }

        if($this->category) {
            $categorySql = 'category = ?';
            $params[] = $this->category;
            $html = "<h2>Visar resultat för kategori: " . $this->category;
        } else {
            $categorySql = 1;
        }

        $sql = "
            SELECT *
            FROM RMContent
            WHERE
                type = 'post' AND
                $slugSql AND
                $categorySql AND
                deleted IS NULL AND
                published <= NOW()
            ORDER BY published DESC
            LIMIT $posts;
        ";

        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, $params);
        if(isset($res[0])) {
            $html .= $this->createBlog($res);
        } else {
            $html = "<p>Sidan kunde inte hittas. Det finns ingen sådan post.</p>"; 
        }

        return $html;
    }



    /**
    * Create the blog site.
    * @param array $data with data to print on the page.
    * @return string the page in HTML.
    */
    private function createBlog($data) {
        $acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;
        $textfilter = new CTextFilter();
        $title = null;
        $html = null;
        //If a slug is passed then the headline is h1> otherwise 
        //it's <h2> and a general headline for the page is set.
        $headline = $this->slug ? "h1" : "h2";
        
        foreach($data as $c) {
            $title  = htmlentities($c->title, null, 'UTF-8');
            $readMoreLink = $this->slug ? null : "<a href='news.php?slug={$c->slug}'>[läs mer]</a>";
            $data = $this->slug ? $c->data : substr($c->data, 0, 200) . "... ";
            $data = $textfilter->doFilter(htmlentities($data, null, 'UTF-8'), $c->filter);
            $data .= $readMoreLink;
            $editLink = $acronym && $this->slug ? "<a href='edit.php?id={$c->id}'>Uppdatera posten</a>" : null;
            $html .= "<{$headline}><a href='news.php?slug={$c->slug}'>" . $title . "</a></{$headline}>";
            $html .= "<p>" . $data . "</p>";
            $html .= "<p class='postInfo'>" . "Kategori: <a href='news.php?category={$c->category}'>" . $c->category . "</a></p>";
            $html .= "<p class='postInfo'>" . "Publicerad: " . $c->published . "</p>";
            $html .= "<p>" . $editLink . "</p>";
        }

        return $html;
    } 


    /**
    * @return string with current slug.
    */
    public function getSlug() {
        return $this->slug;
    }

}