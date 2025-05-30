<?php

namespace AceTheTest_Dashboard\includes;
class LD_Helper
{

    public static function get_latest_test_scores_for_user($user_id, array $course_ids)
    {

        $results = [];

        foreach ($course_ids as $course_id) {
            // Init scores in case no quizzes found
            $course_quiz_avg = 0;
            $lesson_quiz_avg = 0;
            $course_scores = [];
            $lesson_scores = [];

            // 1. Find every quiz that belongs to this course.
            $quizzes = learndash_get_course_quiz_list($course_id); // Legacy but still supported
            if (count($quizzes)) {
                $course_scores = self::get_quiz_scores($user_id, $quizzes);
            }

            // 2. Find every quiz that belongs to this course lessons
            $lessons = learndash_get_lesson_list($course_id);
            foreach ($lessons as $lesson) {
                $quizzes = learndash_get_lesson_quiz_list($lesson->ID);
                $scores = self::get_quiz_scores($user_id, $quizzes);
                $lesson_scores = array_merge($lesson_scores, $scores);
            }

            // Combine and calculate final average
            $all_scores = array_merge($course_scores, $lesson_scores);
            $final_avg = count($all_scores) ? array_sum($all_scores) / count($all_scores) : 0;

            // Append result
            $results[] = [
                'course_id'     => $course_id,
                'course_title'  => get_the_title($course_id),
                'course_url'    => get_permalink($course_id),
                'average_score' => round($final_avg, 2),
            ];
        }

        return $results;
    }

    private static function get_quiz_scores($user_id, $quizzes)
    {
        $scores = [];
        foreach ($quizzes as $quiz) {
            $quiz_id = (int)$quiz['id'];     // key name per API docs
            // 2. Grab ALL attempt activity-IDs for this user+quiz.
            $attempts = learndash_get_user_quiz_attempts($user_id, $quiz_id);
            // 3. Pick the last ID → latest attempt.
            $latest_attempt = end($attempts);
            $meta = learndash_get_activity_meta_fields($latest_attempt->activity_id);
            if (!isset($meta['percentage']) || $meta['percentage'] === '') {
                continue;
            }
            $scores[] = (float)$meta['percentage'];
        }
        return $scores;
    }

    public static function get_study_hours_for_user()
    {

    }
    public static function get_quiz_activities_for_user($user_id,$course_ids)
    {
        $results = [];
        foreach ($course_ids as $course_id) {
            $quizzes_data = [];

            // Get course-level quizzes
            $course_quizzes = learndash_get_course_quiz_list($course_id);

            // Get lesson-level quizzes
            $lessons = learndash_get_lesson_list($course_id);
            foreach ($lessons as $lesson) {
                $lesson_quizzes = learndash_get_lesson_quiz_list($lesson->ID);
                $course_quizzes = array_merge($course_quizzes, $lesson_quizzes);
            }

            foreach ($course_quizzes as $quiz) {
                $quiz_id = (int)$quiz['id'];
                $attempts_data = [];

                $attempts = learndash_get_user_quiz_attempts($user_id, $quiz_id);

                foreach ($attempts as $attempt) {
                    $meta = learndash_get_activity_meta_fields($attempt->activity_id);

                    if (!isset($meta['percentage']) || $meta['percentage'] === '') {
                        continue;
                    }

                    $attempts_data[] = [
                        'score'      => isset($meta['points']) ? (int)$meta['points'] : 0,
                        'questions'  => isset($meta['total_points']) ? (int)$meta['total_points'] : 0,
                        'percentage' => (float)$meta['percentage'],
                        'date'       => date("d-m-Y", $meta['completed'])
                    ];
                }

                // Skip quizzes with no attempts
                if (!empty($attempts_data)) {
                    $quizzes_data[] = [
                        'name'     => $quiz['post']->post_title,
                        'attempts' => $attempts_data,
                    ];
                }
            }

            if (!empty($quizzes_data)) {
                $results[] = [
                    'courseTitle' => get_the_title($course_id),
                    'post_url' => get_permalink($course_id),
                    'quizzes'     => $quizzes_data,
                ];
            }
        }

        return $results;
    }

}
