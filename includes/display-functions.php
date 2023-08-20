<?php
if (!defined('ABSPATH')) {
    exit;
}

include plugin_dir_path(__FILE__) . 'api-handler.php';

class SWS_Display_Functions
{

    private $api_handler;

    public function __construct()
    {
         $this->api_handler = new SWS_API_Handler();

        add_filter('the_content', array($this, 'add_starships_to_content'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts_and_styles'));
    }
    //----------------------------------------------------------------------------------------
 
    public function add_starships_to_content($content)
    {
        $selected_page_id = get_option('sws_selected_page');

        if (is_page($selected_page_id)) {
            $starships = $this->api_handler->fetch_starships();
            if (!is_wp_error($starships)) {
                $content = $this->render_starships_table($starships) . $content;
            } else {
                $content .= '<p>Error fetching starships data.</p>' . $content;
            }
        }

        return $content;
    }
    //----------------------------------------------------------------------------------------

 
    public function enqueue_scripts_and_styles()
    {
        wp_enqueue_style('datatables-css', '//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css');
        wp_enqueue_script('datatables-js', '//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js', array('jquery'), '1.13.6', true);
        
        wp_enqueue_script('sws-js', plugin_dir_url(__FILE__) . '../assets/js/sws.js', array('jquery', 'datatables-js'), SWS_VERSION, true);
        wp_enqueue_style('sws-css', plugin_dir_url(__FILE__) . '../assets/css/sws.css');
    }
    //----------------------------------------------------------------------------------------

 
    private function render_starships_table($starships)
    {
        $output = '<table id="sws-starships-table" class="display">';
        $output .= '<thead>';
        $output .= '<tr>';
        $output .= '<th>Name</th>';
        $output .= '<th>Starship Class</th>';
        $output .= '<th>Crew</th>';
        $output .= '<th>Cost in Credits</th>';
        $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody>';

        foreach ($starships as $starship) {
            $output .= '<tr>';
            $output .= '<td>' . esc_html($starship['name']) . '</td>';
            $output .= '<td>' . esc_html($starship['starship_class']) . '</td>';
            $output .= '<td>' . esc_html($starship['crew']) . '</td>';
            $output .= '<td>' . esc_html($starship['cost_in_credits']) . '</td>';
            $output .= '</tr>';
        }

        $output .= '</tbody>';
        $output .= '</table>';

        return $output;
    }
}

$sws_display = new SWS_Display_Functions();
