<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class SWS_API_Handler {

    /**
     * The base URL for the Star Wars API.
     */
    private $api_url = 'https://swapi.dev/api/starships/';

    /**
     * Fetch starships data from the Star Wars API.
     *
     * @return array|WP_Error Starships data or WP_Error on failure.
     */
    public function fetch_starships() {
        $response = wp_remote_get( $this->api_url );

        if ( is_wp_error( $response ) ) {
            return $response; 
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );

        if ( empty( $data ) || ! isset( $data['results'] ) ) {
            return new WP_Error( 'invalid_data', 'Invalid data received from the Star Wars API.' );
        }

        return $data['results'];
    }

    /**
     * Get specific details of each starship.
     *
     * @param array $starship Full starship data.
     * @return array Filtered details.
     */
    public function get_starship_details( $starship ) {
        return array(
            'name'            => $starship['name'],
            'starship_class'  => $starship['starship_class'],
            'crew'            => $starship['crew'],
            'cost_in_credits' => $starship['cost_in_credits']
        );
    }
}
