<?php

if (!function_exists('format_date')) {
    /**
     * Format Date
     *
     * @param        $date
     * @param string $format
     * @return bool|string
     */
    function format_date($date, $format = "d F Y")
    {
        return date($format, strtotime($date));
    }
}

if (!function_exists('profile_image')) {

    /**
     * Return the path of the logged in user's profile image
     * @return string
     */
    function profile_image()
    {
        $image = user()->image;
        $gender = user()->gender;
        if ($image && strlen($image) > 5) {
            return '/uploads/images/' . $image;
        }
        else {
            return "/images/admin/$gender.png";
        }
    }
}

if (!function_exists('activitiy_after')) {
    /**
     * Get the After Title of model
     * @param $activity
     * @return string
     */
    function activitiy_after($activity)
    {
        if (strlen($activity->after) > 3) {
            return $activity->after;
        }
        else if (isset($activity->subject->title)) {
            return $activity->subject->title;
        }

        return '';
    }
}

function image_row_link($thumb, $image = null)
{
    return "<a target='_blank' href='" . uploaded_images_url(($image ? $image : $thumb)) . "'><img src='" . uploaded_images_url($thumb) . "' style='height: 50px'/></a>";
}
