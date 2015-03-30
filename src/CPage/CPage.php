<?php
/**
* Creates a page.
*
*/
class CPage extends CContent {

    public function getPageContent() {
        $url = isset($_GET['url']) ? $_GET['url'] : null;
        $content = null;

        $sql = "
            SELECT *
            FROM RMContent
            WHERE
                type = 'page' 
                AND url = ? 
                AND published <= NOW();
            ";

        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, array($url));
        
        if(isset($res[0])) {
            $content = $this->createPage($res[0]);
        } else {
            $content = array('title' => "Sidan kunde inte hittas", 
                             'data' => "Det finns ingen sida för den angivna URL:en"); 
        }

        return $content;
    }



    public function getMoviePageContent() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $content = null;

        // $sql = "SELECT * FROM RMMovie WHERE id = ?;";
        $sql = "SELECT M.*, GROUP_CONCAT(G.name) AS genre
                    FROM RMMovie AS M
                        LEFT OUTER JOIN RMMovie2Genre AS M2G
                            ON M.id = M2G.idMovie
                        INNER JOIN RMGenre AS G
                            ON M2G.idGenre = G.id
                        WHERE M.id = ?";


        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, array($id));
        
        if(isset($res[0]) && $id) {
            $content = $this->createMoviePage($res[0]);
        } else {
            $content = array('title' => "Filmen kunde inte hittas", 
                             'data' => "Det finns ingen film med det angivna ID-numret."); 
        }

        return $content;
    }


    private function createPage($data) {
        $textfilter = new CTextFilter();
        $title = null;
        $html = null;

        $title = htmlentities($data->title, null, 'UTF-8');

        $html = "<p>"; 
        $html .= $textfilter->doFilter(htmlentities($data->data, null, 'UTF-8'), $data->filter) ;
        $html .= "</p>";

        return array('title' => $title, 'data' => $html);
    } 


    private function createMoviePage($data) {
        $textfilter = new CTextFilter();
        $title = null;
        $html = null;

        $title = htmlentities($data->title, null, 'UTF-8');
        $html = "<div class='leftThirdContent'><img src='img.php?src=../{$data->image}&width=250' title='{$data->title}' alt='{$data->title}'></div>";
        $html .= "<div class='leftTwoThirdContent'><p><span class='bold'>År:</span> " . $data->year . 
                 " <span class='bold'>Genre: </span>" . $data->genre; 
        $html .= "</p><p class='bold'><a href='{$data->imdb}' target='_blank'>(IMDB information)</a> <a href='{$data->youtube}' target='_blank'>(Trailer)</a><p>"; 
        $html .= "</p><h3>Handling:</h3><p>"; 
        $html .= $textfilter->doFilter(htmlentities($data->plot, null, 'UTF-8'), $data->filter);
        $html .= "</p><p><span class='bold'>Pris:</span> {$data->price} kr";
        $html .= "</p><p><button class='button'>Hyr</button></p></div>";

        return array('title' => $title, 'data' => $html);
    } 

    

}