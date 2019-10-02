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
        	if (is_slug_url($image)) {
                return $image;
            }
            
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

if (!function_exists('photo_url')) {
    function photo_url($name)
    {
        return config('app.url') . '/uploads/photos/' . $name;
    }
}

/**
 * Fetch the website settings from session or database
 */
if (!function_exists('settings')) {

    /**
     * @param bool $forceNew
     * @return \Illuminate\Session\SessionManager|\Illuminate\Session\Store|mixed
     */
    function settings($forceNew = false)
    {
        if (!$forceNew) {
            // fetch settings from session
            if (session()->has("titan.settings")) {
                return session("titan.settings");
            }
        }

        // fetch settings or create if not exist
        $settings = \Bpocallaghan\Titan\Models\Settings::find(1);
        if (is_null($settings)) {
            $settings = \Bpocallaghan\Titan\Models\Settings::create([
                'name'        => config('app.name'),
                'description' => config('app.description'),
                'author'      => config('app.author'),
                'keywords'    => config('app.keywords'),
            ]);
        }

        session()->put("titan.settings", $settings);

        return $settings;
    }
}
