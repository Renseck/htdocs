<?php

namespace App\factories\formfactory;

class Form
{
    // =============================================================================================
    public function showForm(string $page, string $action, string $method, array $fields, string $submit_caption, array $attributes) : void
    {
        $this->openForm($page, $action, $method, $attributes);
        $this->showFields($fields);
        $this->closeForm($submit_caption);
    }

    // =============================================================================================
    public function openForm(string $page, string $action, string $method, array $attributes) : void
    {
        $attrStr = '';
        foreach ($attributes as $key => $value)
        {
            $attrStr .= ' ' . htmlspecialchars($key) . '="' . htmlspecialchars($value) . '"';
        }

        echo '<form action="' . htmlspecialchars($action) . '" method="' . htmlspecialchars($method) 
             .'"' . $attrStr . '>' . PHP_EOL
             .'<input type="hidden" name="page" value="' . $page . '" />' . PHP_EOL;
    }

    // =============================================================================================
    public function showField(string $field_name, array $field_info) : void
    {
        $fieldHtml = '<div class="form-group">' . PHP_EOL
                    .'  <label for="' . $field_name . '">' . $field_info['label'] . '</label>' . PHP_EOL;

        switch ($field_info['type'])
        {
            case "textarea":
                $fieldHtml .= '     <textarea name="' . $field_name . '"></textarea>' . PHP_EOL;
                break;
            default: 
                $fieldHtml .= '     <input type="' . $field_info['type'] . '" name="' . $field_name .'" />' . PHP_EOL;
                break;
        }
        $fieldHtml .= '</div>' . PHP_EOL;
                     
        echo $fieldHtml;
    }

    // =============================================================================================
    public function showFields(array $fields) : void
    {
        foreach ($fields as $name => $info)
        {
            $this->showField(
                field_name : $name,
                field_info: $info
            );
        }
    }

    // =============================================================================================
    public function closeForm(string $submit_caption) : void 
    {
        $html = '<div class="form-group">' . PHP_EOL
               .'<button type="submit" value="submit">' . $submit_caption . '</button>' . PHP_EOL
               .'</div>' . PHP_EOL
               .'</form>' . PHP_EOL;
               
            
        echo $html;
    }
}
