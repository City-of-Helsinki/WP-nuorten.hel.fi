<?php
/**
 * Components Demo template. Use page slug hds-components-demo to see it.
 */

get_header();

?>

<div class="container container--full">
    <h2>Full-width container</h2>
</div>
<div class="container">

    <h2>Buttons</h2>

    <h3>Primary</h3>

    <?php
    get_template_part( 'partials/button', '', [
        'classes' => [ 'primary' ],
        'label'   => 'Primary button',
	] );
    ?>

    <h3>Secondary</h3>

    <?php
    get_template_part( 'partials/button', '', [
        'classes' => [ 'secondary' ],
        'label'   => 'Secondary button',
	] );
    ?>


    <h3>Supplementary</h3>


    <?php
    get_template_part( 'partials/button', '', [
        'classes' => [ 'supplementary' ],
        'label'   => 'supplementary button',
	] );
    ?>

    <h3>Full width</h3>

    <?php
    get_template_part( 'partials/button', '', [
        'classes' => [ 'primary', 'fullwidth' ],
        'label'   => 'Primary full width button',
	] );
    ?>

    <h3>Small</h3>


    <?php
    get_template_part( 'partials/button', '', [
        'classes' => [ 'primary', 'small' ],
        'label'   => 'Small button',
	] );
    ?>

    <h3>Disabled</h3>

    <?php
    get_template_part( 'partials/button', '', [
        'classes'    => [ 'primary' ],
        'label'      => 'Disabled button',
        'attributes' => [
			'disabled' => true,
        ],
	] );
    ?>

    <h3>Buttons with icons</h3>


    <?php
    get_template_part( 'partials/button', '', [
        'classes'    => [ 'primary' ],
        'label'      => 'Share',
        'icon-start' => 'share',
	] );
    ?>

    <?php
    get_template_part( 'partials/button', '', [
        'classes'  => [ 'primary' ],
        'label'    => 'Share',
        'icon-end' => 'angle-right',
	] );
    ?>

    <?php
    get_template_part( 'partials/button', '', [
        'classes'    => [ 'primary' ],
        'label'      => 'Share',
        'icon-start' => 'share',
        'icon-end'   => 'angle-right',
	] );
    ?>

    <br>
    <br>

    <?php
    get_template_part( 'partials/button', '', [
        'classes'    => [ 'primary', 'small' ],
        'label'      => 'Share',
        'icon-start' => 'share',
	] );
    ?>

    <?php
    get_template_part( 'partials/button', '', [
        'classes'  => [ 'primary', 'small' ],
        'label'    => 'Share',
        'icon-end' => 'angle-right',
	] );
    ?>

    <?php
    get_template_part( 'partials/button', '', [
        'classes'    => [ 'primary', 'small' ],
        'label'      => 'Share',
        'icon-start' => 'share',
        'icon-end'   => 'angle-right',
	] );
    ?>

    <h3>Themes</h3>

    <?php
    get_template_part( 'partials/button', '', [
        'classes' => [ 'primary', 'theme-coat' ],
        'label'   => 'Coat',
	] );
    ?>

    <?php
    get_template_part( 'partials/button', '', [
        'classes' => [ 'secondary', 'theme-coat' ],
        'label'   => 'Coat',
	] );
    ?>

    <?php
    get_template_part( 'partials/button', '', [
        'classes' => [ 'supplementary', 'theme-coat' ],
        'label'   => 'Coat',
	] );
    ?>

    <br>
    <br>

    <?php
    get_template_part( 'partials/button', '', [
        'classes' => [ 'primary', 'theme-black' ],
        'label'   => 'black',
	] );
    ?>

    <?php
    get_template_part( 'partials/button', '', [
        'classes' => [ 'secondary', 'theme-black' ],
        'label'   => 'black',
	] );
    ?>

    <?php
    get_template_part( 'partials/button', '', [
        'classes' => [ 'supplementary', 'theme-black' ],
        'label'   => 'black',
	] );
    ?>
</div>
<div class="container">

    <h2>Checboxes</h2>

    <h4>Default</h4>
    <?php
    get_template_part( 'partials/checkbox', '', [
        'value' => 'foo',
        'label' => 'Option',
        'name'  => 'default',
        'id'    => 'default',
	] );
    ?>

    <h4>Default with generated unique id</h4>
    <?php
    get_template_part( 'partials/checkbox', '', [
        'value' => 'foo',
        'label' => 'Option',
        'name'  => 'default2',
	] );
    ?>

    <h4>Selected</h4>
    <?php
    get_template_part( 'partials/checkbox', '', [
        'name'    => 'selected',
        'value'   => 'bar',
        'label'   => 'Option',
        'id'      => 'selected',
        'checked' => true,
	] );
    ?>
    
    <h4>Disabled</h4>
    <?php
    get_template_part( 'partials/checkbox', '', [
        'value'    => 'baz',
        'label'    => 'Option',
        'name'     => 'disabled',
        'id'       => 'disabled',
        'disabled' => true,
	] );
    ?>

    <h4>SelectedDisabled</h4>
    <?php
    get_template_part( 'partials/checkbox', '', [
        'value'    => 'bax',
        'label'    => 'Option',
        'name'     => 'selecteddisabled',
        'id'       => 'selecteddisabled',
        'disabled' => true,
        'checked'  => true,
	] );
    ?>
</div>

<div class="container">
    <h2>Notifications</h2>

    <?php
    get_template_part( 'partials/notification', '', [
        'icon'  => 'info-circle',
        'label' => 'Notification',
        'text'  => 'Kerän pistin kelkkahani, sommelon rekoseheni; ve’in kelkalla kotihin, rekosella riihen luoksi; panin aitan parven päähän vaskisehen vakkasehen. Viikon on virteni vilussa, kauan kaihossa sijaisnut.',
	] );
    ?>
    <br><br>

    <?php
    get_template_part( 'partials/notification', '', [
        'classes' => [ 'success' ],
        'icon'    => 'check',
        'label'   => 'Success!',
        'text'    => 'Rekosella riihen luoksi; panin aitan parven päähän vaskisehen vakkasehen. Viikon on virteni vilussa, kauan kaihossa sijaisnut.',
	] );
    ?>
    <br><br>

    <?php
    get_template_part( 'partials/notification', '', [
        'classes' => [ 'alert' ],
        'icon'    => 'alert-circle',
        'label'   => 'Alert',
        'text'    => 'Kerän pistin kelkkahani, sommelon rekoseheni; ve’in kelkalla kotihin, rekosella riihen luoksi; panin aitan parven päähän vaskisehen vakkasehen. Viikon on virteni vilussa, kauan kaihossa sijaisnut.',
	] );
    ?>
    <br><br>

    <?php
    get_template_part( 'partials/notification', '', [
        'classes' => [ 'error' ],
        'icon'    => 'error',
        'label'   => 'Error',
        'text'    => 'Kerän pistin kelkkahani, sommelon rekoseheni; ve’in kelkalla kotihin, rekosella riihen luoksi; panin aitan parven päähän vaskisehen vakkasehen. Viikon on virteni vilussa, kauan kaihossa sijaisnut.',
	] );
    ?>
    <br><br>

    <h3>Large</h3>

    <?php
    get_template_part( 'partials/notification', '', [
        'classes' => [ 'large' ],
        'icon'    => 'info-circle',
        'label'   => 'Large notification',
        'text'    => 'Rekosella riihen luoksi; panin aitan parven päähän vaskisehen vakkasehen. Viikon on virteni vilussa, kauan kaihossa sijaisnut.',
	] );
    ?>
    <br><br>    

    <h3>Small</h3>

    <?php
    get_template_part( 'partials/notification', '', [
        'classes' => [ 'small' ],
        'icon'    => 'info-circle',
        'text'    => 'Notification',
	] );
    ?>
    <br><br>

    <?php
    get_template_part( 'partials/notification', '', [
        'classes' => [ 'success', 'small' ],
        'icon'    => 'check',
        'text'    => 'Success!',
	] );
    ?>
    <br><br>

    <?php
    get_template_part( 'partials/notification', '', [
        'classes' => [ 'alert', 'small' ],
        'icon'    => 'alert-circle',
        'text'    => 'Alert',
	] );
    ?>
    <br><br>

    <?php
    get_template_part( 'partials/notification', '', [
        'classes' => [ 'error', 'small' ],
        'icon'    => 'error',
        'text'    => 'Error',
	] );
    ?>
    <br><br>    

    <h3>With close buttons</h3>

    <?php
    get_template_part( 'partials/notification', '', [
        'icon'  => 'info-circle',
        'label' => 'Notification with close button',
        'text'  => 'Rekosella riihen luoksi; panin aitan parven päähän vaskisehen vakkasehen. Viikon on virteni vilussa, kauan kaihossa sijaisnut.',
        'close' => true,
	] );
    ?>
    <br><br>    

    <?php
    get_template_part( 'partials/notification', '', [
        'classes' => [ 'small' ],
        'icon'    => 'info-circle',
        'text'    => 'Small with close button',
        'close'   => true,
	] );
    ?>
    <br><br>    


    <h3>Visually hidden notification</h3>

    <?php
    get_template_part( 'partials/notification', 'hidden', [
        'text' => 'This text is only available to assistive technology',
	] );
    ?>
</div>

<div class="container">
    <h2>Radiobuttons</h2>

    <h4>Default</h4>
    <?php
    get_template_part( 'partials/radio', '', [
        'value' => 'foo',
        'label' => 'Option',
        'name'  => 'defaultradio',
        'id'    => 'defaultradio',
	] );
    ?>

    <h4>Default with generated unique id</h4>
    <?php
    get_template_part( 'partials/radio', '', [
        'value' => 'foo',
        'label' => 'Option',
        'name'  => 'defaultradio',
	] );
    ?>

    <h4>Selected</h4>
    <?php
    get_template_part( 'partials/radio', '', [
        'name'    => 'defaultradio',
        'value'   => 'bar',
        'label'   => 'Option',
        'id'      => 'selectedradio',
        'checked' => true,
	] );
    ?>
    
    <h4>Disabled</h4>
    <?php
    get_template_part( 'partials/radio', '', [
        'value'    => 'baz',
        'label'    => 'Option',
        'name'     => 'defaultradio',
        'id'       => 'disabledradio',
        'disabled' => true,
	] );
    ?>

    <h4>SelectedDisabled</h4>
    <?php
    get_template_part( 'partials/radio', '', [
        'value'    => 'bax',
        'label'    => 'Option',
        'name'     => 'selecteddisabledradio',
        'id'       => 'selecteddisabledradio',
        'disabled' => true,
        'checked'  => true,
	] );
    ?>

    <h2>Status labels</h2>

    <?php
    get_template_part( 'partials/label', '', [
        'label' => 'Default',
	] );
    ?>
    <br><br>

    <?php
    get_template_part( 'partials/label', '', [
        'classes' => [ 'info' ],
        'label'   => 'Info',
	] );
    ?>
    <br><br>

    <?php
    get_template_part( 'partials/label', '', [
        'classes' => [ 'success' ],
        'label'   => 'Success',
	] );
    ?>
    <br><br>

    <?php
    get_template_part( 'partials/label', '', [
        'classes' => [ 'alert' ],
        'label'   => 'Alert',
	] );
    ?>
    <br><br>

    <?php
    get_template_part( 'partials/label', '', [
        'classes' => [ 'error' ],
        'label'   => 'Error',
	] );
    ?>
    <br><br>

    <h2>Text inputs</h2>

    <div style="max-width: 400px">
        <h4>Default</h4>
        <?php
        get_template_part( 'partials/textfield', '', [
            'label'          => 'Label text',
            'value'          => '', // Use if you want to prefill the value
            'name'           => 'inputdefault',
            'id'             => 'inputdefault',
            'placeholder'    => 'Placeholder text',
            'type'           => 'text',
            'assistive_text' => 'Some assistive text',
		] );
        ?>

        <h4>ReadOnly</h4>
        <?php
        get_template_part( 'partials/textfield', '', [
            'label'          => 'Label text',
            'value'          => 'This is readonly', // Use if you want to prefill the value
            'name'           => 'inputdefault2',
            'id'             => 'inputdefault2',
            'type'           => 'text',
            'assistive_text' => 'Some assistive text',
            'readonly'       => true,
		] );
        ?>
        
        <h4>Disabled</h4>
        <?php
        get_template_part( 'partials/textfield', '', [
            'label'          => 'Label text',
            'name'           => 'input3',
            'id'             => 'input3',
            'placeholder'    => 'Placeholder text',
            'type'           => 'text',
            'assistive_text' => 'Some assistive text',
            'disabled'       => true,
		] );
        ?>
        <h4>Invalid</h4>
        <?php
        get_template_part( 'partials/textfield', '', [
            'label'          => 'Label text',
            'name'           => 'input4',
            'value'          => 'Something wrong here',
            'id'             => 'input4',
            'type'           => 'text',
            'assistive_text' => 'Check the value and resubmit',
            'invalid'        => true,
		] );
        ?>
        <h4>Required</h4>
        <?php
        get_template_part( 'partials/textfield', '', [
            'label'          => 'Label text',
            'value'          => '', // Use if you want to prefill the value
            'name'           => 'input5',
            'id'             => 'input5',
            'placeholder'    => 'Placeholder text',
            'type'           => 'text',
            'assistive_text' => 'Some assistive text',
            'required'       => true,
		] );
        ?>
    </div>

    <h2>Textarea</h2>

    <div style="max-width: 400px">
    <h4>Default</h4>
        <?php
        get_template_part( 'partials/textarea', '', [
            'label'          => 'Label text',
            'value'          => 'Kuusi kultaista munoa, rautamunan seitsemännen.', // Use if you want to prefill the value
            'name'           => 'textareadefault',
            'id'             => 'textareadefault',
            'placeholder'    => 'Placeholder text',
            'type'           => 'text',
            'assistive_text' => 'Some assistive text',
		] );
        ?>

        <h4>ReadOnly</h4>
        <?php
        get_template_part( 'partials/textarea', '', [
            'label'          => 'Label text',
            'value'          => 'This is readonly', // Use if you want to prefill the value
            'name'           => 'textareadefault2',
            'id'             => 'textareadefault2',
            'type'           => 'text',
            'assistive_text' => 'Some assistive text',
            'readonly'       => true,
		] );
        ?>
        
        <h4>Disabled</h4>
        <?php
        get_template_part( 'partials/textarea', '', [
            'label'          => 'Label text',
            'name'           => 'textarea3',
            'id'             => 'textarea3',
            'placeholder'    => 'Placeholder text',
            'type'           => 'text',
            'assistive_text' => 'Some assistive text',
            'disabled'       => true,
		] );
        ?>
        <h4>Invalid</h4>
        <?php
        get_template_part( 'partials/textarea', '', [
            'label'          => 'Label text',
            'name'           => 'textarea4',
            'value'          => 'Something wrong here',
            'id'             => 'textarea4',
            'type'           => 'text',
            'assistive_text' => 'Check the value and resubmit',
            'invalid'        => true,
		] );
        ?>
        <h4>Required</h4>
        <?php
        get_template_part( 'partials/textarea', '', [
            'label'          => 'Label text',
            'value'          => '', // Use if you want to prefill the value
            'name'           => 'textarea5',
            'id'             => 'textarea5',
            'placeholder'    => 'Placeholder text',
            'type'           => 'text',
            'assistive_text' => 'Some assistive text',
            'required'       => true,
		] );
        ?>
    </div>
</div>



<?php

get_footer();
