<?php

namespace monotone;

class GravityForms {
    public function __construct() {
        add_shortcode( 'all_quiz_results', [ $this, 'gf_value_shortcode' ] );

        add_filter( 'gform_progressbar_start_at_zero', '__return_true' );

        add_filter( 'gform_progress_bar', function ( $progress_bar, $form, $confirmation_message ) {
            $progress_bar = str_replace( 'Step', 'Question', $progress_bar );

            return $progress_bar;
        }, 10, 3 );

        add_filter( 'gquiz_correct_indicator', function ( $correct_indicator_img ) {
            if ( file_exists( THEME_ASSETS_PATH . '/build/images/icon-quiz-green-check.svg' ) ) {
                return THEME_ASSETS_URI . '/build/images/icon-quiz-green-check.svg';
            }

            return $correct_indicator_img;
        }, 10, 1 );

        add_filter( 'gquiz_incorrect_indicator', function ( $incorrect_indicator_img ) {
            if ( file_exists( THEME_ASSETS_PATH . '/build/images/icon-quiz-red-check.svg' ) ) {
                return THEME_ASSETS_URI . '/build/images/icon-quiz-red-check.svg';
            }

            return $incorrect_indicator_img;
        }, 10, 1 );

    }

    /**
     * Get the quiz results for a given entry
     *
     * @param $atts
     *
     * @return mixed|string
     */
    /**
     * Get the quiz results for a given entry
     *
     * @param $atts
     *
     * @return mixed|string
     */
    function gf_value_shortcode( $atts ) {
        if ( ! class_exists( 'GFQuiz' ) || ! isset( $_REQUEST[ 'entry_id' ] ) ) {
            return '';
        }

        // Set default attributes and extract them
        $atts = shortcode_atts( [
            'form_id' => false,
            'heading' => 'Your results'
        ], $atts );

        // get form by id
        $form_id = $atts[ 'form_id' ];
        if ( ! $form_id ) {
            return 'No form ID provided.';
        }


        $form = \GFAPI::get_form( $form_id );
        if ( ! $form ) {
            return 'Form not found.';
        }

        $entry = \GFAPI::get_entry( $_REQUEST[ 'entry_id' ] );
        $quiz  = \GFQuiz::get_instance();

        // make sure there are quiz fields on the form
        $quiz_fields = \GFAPI::get_fields_by_type( $form, [ 'quiz' ] );
        if ( empty( $quiz_fields ) ) {
            return 'No quiz fields found in the form.';
        }

        $quiz_results = $quiz->get_quiz_results( $form, $entry );

        $number_of_questions       = count( $quiz_results[ 'fields' ] );
        $yes = $number_of_questions - $quiz_results[ 'score' ];
        $no = $quiz_results[ 'score' ];

        $heading = "<h4>{$atts[ 'heading' ]}: \"Yes\"  = {$yes}</h4>";

        $results = '<div id="gquiz_confirmation_message">' . $heading . $quiz_results[ 'summary' ] . '</div>';

        return $results;
    }

}

new GravityForms();
