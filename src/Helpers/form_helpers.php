<?php

if (!function_exists('form_error_class')) {
    function form_error_class($attribute, $errors)
    {
        return $errors->first($attribute, 'has-error');
    }
}

if (!function_exists('form_error_message')) {
    function form_error_message($attribute, $errors)
    {
        return $errors->first($attribute,
            '<i><small for="' . $attribute . '" class="text-red">:message</small></i>');
    }
}

if (!function_exists('action_row')) {
    /**
     * Get the html for the actions for a table list
     * @param            $url
     * @param            $id
     * @param            $title
     * @param array      $actions
     * @return string
     */
    function action_row($url, $id, $title, $actions = ['show', 'edit', 'delete'])
    {
        $show = '<div class="btn-group">
				    <a href="' . $url . $id . '" class="btn btn-default btn-xs" data-toggle="tooltip" title="Show ' . $title . '">
					    <i class="fa fa-eye"></i>
				    </a>
			    </div>';

        $edit = '<div class="btn-group">
				    <a href="' . $url . $id . '/edit' . '" class="btn btn-primary btn-xs" data-toggle="tooltip" title="Edit ' . $title . '">
					    <i class="fa fa-edit"></i>
				    </a>
			    </div>';

        $delete = '<div class="btn-group">
				        <form id="form-delete-row' . $id . '" method="POST" action="' . $url . $id . '">
				        <input name="_method" type="hidden" value="DELETE">
				        <input name="_token" type="hidden" value="' . csrf_token() . '">
					    <input name="_id" type="hidden" value="' . $id . '">
					    <a data-form="form-delete-row' . $id . '" class="btn btn-danger btn-xs btn-delete-row" data-toggle="tooltip" title="Delete ' . $title . '">
						    <i class="fa fa-times"></i>
					    </a>
					    </form>
				    </div>';

        $html = '';
        foreach ($actions as $k => $action) {
            if ($action == 'show') {
                $html .= $show;
            }
            if ($action == 'edit') {
                $html .= $edit;
            }
            if ($action == 'delete') {
                $html .= $delete;
            }

            if (is_array($action)) {
                $key = key($action);
                $urll = $action[$key];

                $html .= '<div class="btn-group">
                    <a href="' . $urll . '" class="btn btn-primary btn-xs" data-toggle="tooltip" title="Show ' . $key . ' for ' . $title . '">
                        <i class="fa fa-' . $key . '"></i>
                    </a>
                </div>';
            }
        }

        return '<div class="btn-toolbar">' . $html . '</div>';
    }
}

if (!function_exists('form_select')) {

    function select_option($display, $value, $selected)
    {
        $selected = select_selected($value, $selected);

        $options = ['value' => $value, 'selected' => $selected];

        return '<option' . select_attributes($options) . '>' . e($display) . '</option>';
    }

    function select_selected($value, $selected)
    {
        if (is_array($selected)) {
            return in_array($value, $selected) ? 'selected' : null;
        }

        return ((string) $value == (string) $selected) ? 'selected' : null;
    }

    function select_attributes($attributes)
    {
        $html = [];

        foreach ((array) $attributes as $key => $value) {
            $element = select_attribute_element($key, $value);

            if (!is_null($element)) {
                $html[] = $element;
            }
        }

        return count($html) > 0 ? ' ' . implode(' ', $html) : '';
    }

    function select_attribute_element($key, $value)
    {
        if (is_numeric($key)) {
            $key = $value;
        }

        if (!is_null($value)) {
            return $key . '="' . e($value) . '"';
        }
    }

    /**
     * Simple form select options
     * @param $name
     * @param $list
     * @param $selected
     * @param $options
     * @return string
     */
    function form_select($name, $list, $selected, $options)
    {
        $options['id'] = $name;

        if (!isset($options['name'])) {
            $options['name'] = $name;
        }

        $html = [];
        foreach ($list as $value => $display) {
            $html[] = select_option($display, $value, $selected);
        }

        // Once we have all of this HTML, we can join this into a single element after
        // formatting the attributes into an HTML "attributes" string, then we will
        // build out a final select statement, which will contain all the values.
        $options = select_attributes($options);

        $list = implode('', $html);

        return "<select{$options}>{$list}</select>";
    }
}