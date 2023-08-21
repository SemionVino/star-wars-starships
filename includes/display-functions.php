<?php
if (!defined('ABSPATH')) {
    exit;
}

// Include the API Handler class.
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
    /**
     * Add starships data to the content of the selected page.
     *
     * @param string $content The original content.
     * @return string Modified content.
     */
    public function add_starships_to_content($content)
    {
        $selected_page_id = get_option('sws_selected_page');

        if (is_page($selected_page_id)) {
            $starships = $this->api_handler->fetch_starships();

            if (!is_wp_error($starships)) {
                $content = $this->render_starships_table($starships) . $content;
            } else {
                $content = '<p>Error fetching starships data.</p>' . $content;
            }
        }

        return $content;
    }
    //----------------------------------------------------------------------------------------

    /**
     * Enqueue front-end scripts and styles.
     */
    public function enqueue_scripts_and_styles()
    {
        wp_enqueue_style('datatables-css', '//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css');
        wp_enqueue_script('datatables-js', '//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js', array('jquery'), '1.13.6', true);
        wp_enqueue_style('bootstrap-css', '//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css');
        wp_enqueue_style('bootstrap-css2', '//cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css');

        wp_enqueue_script('sws-js', plugin_dir_url(__FILE__) . '../assets/js/sws.js', array('jquery', 'datatables-js'), SWS_VERSION, true);
        wp_enqueue_style('sws-css', plugin_dir_url(__FILE__) . '../assets/css/sws.css');
    }
    //----------------------------------------------------------------------------------------

    /**
     * Render starships data as a table.
     *
     * @param array $starships Starships data.
     * @return string HTML table.
     */
    private function render_starships_table($starships)
    {
        $output = '<div class="d-flex justify-content-center">';
        $output .= '<table id="sws-starships-table" class="display cell-border table-bordered  table table-striped">';
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
            //----------------------------------------- Cost row sort handling
            $cost = $starship['cost_in_credits'];
            $sortValueCost = 0;
            if (is_numeric($cost)) {
                $sortValueCost = (int) $cost;
            }
            //-----------------------------------------
            //----------------------------------------- Crew row sort handling
            $crew = $starship['crew'];
            $sortValueCrew = 0;
            $crewNumeric = str_replace(',', '', $crew);

            // Check if it's a range
            if (strpos($crewNumeric, '-') !== false) {
                $rangeParts = explode('-', $crewNumeric);
                $sortValueCrew = (int) $rangeParts[0]; // using the lower number
            } elseif (is_numeric($crewNumeric)) {
                $sortValueCrew = (int) $crewNumeric;
            }
            //-----------------------------------------

            $output .= '<tr>';
            $output .= '<td>' . esc_html($starship['name']) . '</td>';
            $output .= '<td>' . esc_html($starship['starship_class']) . '</td>';
            $output .= '<td data-sort="' . $sortValueCrew . '">' . esc_html($crew) . '</td>';
            $output .= '<td data-sort="' . $sortValueCost . '">' .  esc_html($cost) . '</td>';
            $output .= '</tr>';
        }

        $output .= '</tbody>';
        $output .= '</table>';
        $output .= '</div>';

        return $output;
    }
}

$sws_display = new SWS_Display_Functions();
