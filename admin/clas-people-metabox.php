<?php
  // bounce non-user - if this file is called directly, abort.
  if ( ! defined( 'ABSPATH' ) ) {
    exit;
  }

  // register meta box || create a list
  function clas_team_members_add_meta_box() {
    $post_types = array( 'clas_team_members' );
    foreach ( $post_types as $post_type ) {
      add_meta_box(
        'member_info_meta_box',            // Unique ID of meta box
        'Team Member Information',         // Title of meta box
        'member_info_display_meta_box',    // Callback function
        $post_type                         // Post type
      );
    }
  }
  add_action( 'add_meta_boxes', 'clas_team_members_add_meta_box' );


  // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
  // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
  // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
  // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
  // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
  // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
  // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
  // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
  // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
  // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
  // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
  // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
  // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
  // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---


  // display meta box
  function member_info_display_meta_box( $post ) { ?>

    <style media="screen">

      p.graph_days_officeHours {
        font-size: 2rem;
      }

      div#titlediv, div#post-body-content {
        margin-bottom: 0;
      }

      /* remove space between "add title and team member infomation" */
      div#edit-slug-box {
        margin-top: 0;
        min-height: 0;
      }

      #postbox-container-2 div#normal-sortables {
        min-height: 1rem;
      }

      .container_teachingDays {
        border: 1px solid #c0c0c0;
        box-shadow: 0.3rem 0.3rem 0.3rem #f0f0f0;
        margin: 0 .5rem;
        width: 175px;
        min-width: 100px;
        padding: 1rem;
        display: inline-block;
      }

      table {
        display: block;
        margin-right: 0;
        padding-right: 0;
      }

      .container_group_timeslot {
        border: 2px solid #c0c0c0;
        border-radius: .3rem;
        box-shadow: .3rem .3rem .3rem #fdfdfd;
        padding: .8rem;
        margin: .5rem 0;
      }

      .container_officeHours_inputs {
        width: 340px;
        display: inline-block;
      }

      #large_officeDays_heading {
        font-size: 1.1rem;
      }

      #makeBig {
        font-size: 3rem;
      }

      /* javascript styles */ /* javascript styles */	/* javascript styles */
      /* javascript styles */ /* javascript styles */	/* javascript styles */
      /* javascript styles */ /* javascript styles */	/* javascript styles */
      /* javascript styles */ /* javascript styles */	/* javascript styles */
      /* javascript styles */ /* javascript styles */	/* javascript styles */
      /* javascript styles */ /* javascript styles */	/* javascript styles */
      /* javascript styles */ /* javascript styles */	/* javascript styles */
      /* javascript styles */ /* javascript styles */	/* javascript styles */
      /* javascript styles */ /* javascript styles */	/* javascript styles */
      /* javascript styles */ /* javascript styles */	/* javascript styles */

      /* styling to make it look like a link */
      p.clicker {
        padding: 0;
        padding-top: .6rem;
        margin: 0 auto;
        color: #2A5DB0;
        cursor: pointer;
      }

      /* styling to make it look like a link */
      p.clicker:hover {
        text-decoration: underline;
      }

      /* triggers are hard-coded, to then be individually displayed by JavaScript */
      #trigger_monday_1,
      #trigger_monday_2,
      #trigger_monday_3,
      #trigger_tuesday_1,
      #trigger_tuesday_2,
      #trigger_tuesday_3,
      #trigger_wednesday_1,
      #trigger_wednesday_2,
      #trigger_wednesday_3,
      #trigger_thursday_1,
      #trigger_thursday_2,
      #trigger_thursday_3,
      #trigger_friday_1,
      #trigger_friday_2,
      #trigger_friday_3,
      #trigger_saturday_1,
      #trigger_saturday_2,
      #trigger_saturday_3 {
        display: none;
      }

      #container_unsetCheckboxes {
        margin: 15px 0 15px 10px;
      }

    </style>

    <?php
      $value = get_post_meta( $post->ID, '_member_info_meta_key', true );
      wp_nonce_field( basename( __FILE__ ), 'member_info_meta_box_nonce' );
      $gateList          = array();
      $list_officeDays   = array("monday", "tuesday", "wednesday", "thursday", "friday", "saturday");
    ?>

    <h3>Brief Bio Slug</h3>
    <p>Here you can share a snippet about your team member, like courses, fun facts and other conveyances.<br>Left column text, under the profile image</p>

    <?php

      $content = get_post_meta($post->ID, 'main_info', true);
      // This function adds the WYSIWYG Editor
      wp_editor ($content , 'main_info', array( "media_buttons" => true ));

    ?>

    <div class="null">
      <h3 style="margin: 20px 0 0 0;">Full Bio</h3>
      <p>Here you can display team member's recent projects, publications, bio, etc.</p>
      <?php
        $member_moreInfo = get_post_meta( $post->ID, 'member-moreInfo', true );
        //This function adds the WYSIWYG Editor
        wp_editor ($member_moreInfo ,'member-moreInfo',array ( "media_buttons" => true ));
      ?>
    </div>
    <!-- null div -->

    <?php
      // parsed throughout .contact-info-member
     $member_position   = get_post_meta( $post->ID, 'member-position',   true );
     $member_email      = get_post_meta( $post->ID, 'member-email',      true );
     $member_website    = get_post_meta( $post->ID, 'member-website',    true );
     $member_phone      = get_post_meta( $post->ID, 'member-phone',      true );
     $member_office     = get_post_meta( $post->ID, 'member-office',     true );
     $member_department = get_post_meta( $post->ID, 'member-department', true );
     // $member_officeHours = get_post_meta( $post->ID, 'member-officeHours', true );
    ?>
    <!-- core six details: position, email, website, phone, office, department -->
    <div class="contact-info-member" style="display: flex; flex-wrap: wrap;">
      <div style="margin: 20px 10px; width: 45%;">
        <label for="member-position">Position</label>
        <input name="member-position" value="<?php echo esc_textarea( $member_position ); ?>" class="widefat" rows="4" cols="10">
      </div>
      <div style="margin: 20px 10px; width: 45%;">
        <label for="member-email">Email</label>
        <input type="email" name="member-email" value="<?php echo esc_textarea( $member_email ); ?>" class="widefat">
      </div>
      <div style="margin: 20px 10px; width: 45%;">
        <label for="member-website">Website</label>
        <input type="url" name="member-website" value="<?php echo esc_textarea( $member_website ); ?>" class="widefat">
      </div>
      <div style="margin: 20px 10px; width: 45%;">
        <label for="member-phone">Telephone</label>
        <input type="tel" name="member-phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" value="<?php echo esc_textarea( $member_phone ); ?>" class="widefat">
        <p style="font-style: italic; margin-top: 0;">Format example: 352-555-5555</p>
      </div>
      <div style="margin: 20px 10px; width: 45%;">
        <label for="member-office">Office Address</label>
        <input name="member-office" value="<?php echo esc_textarea( $member_office ); ?>" class="widefat" rows="4" cols="10">
      </div>
      <div style="margin: 20px 10px; width: 45%;">
        <label for="member-department">Department</label>
        <input name="member-department" value="<?php echo esc_textarea( $member_department ); ?>" class="widefat" rows="4" cols="10">
      </div>
    </div>
    <!-- .contact info member -->

    <?php
      // jump :: sketchy includes
      // echo $root_link
      // formatted as :: $period_monday_1 = get_post_meta( $post->ID, 'period_monday_1', true );
      $inc_file_scheduleSlots  = plugin_dir_path(__FILE__);
      $inc_file_scheduleSlots  = str_replace("admin/", "inc/", $inc_file_scheduleSlots);
      $inc_file_scheduleSlots .= "vars_schedule_slots.php";
      include($inc_file_scheduleSlots);
    ?>

    <h3>Teaching Schedule</h3>
    <div id="container_master_schedule">
      <?php foreach ($list_officeDays as $day) { ?>
        <div class="container_teachingDays">
          <h4><?php echo ucfirst($day); ?></h4>
          <?php for ($i = 1; $i < 11; $i++) { ?>
            <input type="checkbox" id="period_<?php echo $day; ?>_<?php echo $i; ?>" name="period_<?php echo $day; ?>_<?php echo $i; ?>" value="period_<?php echo $day; ?>_<?php echo $i; ?>" <?php if ( get_post_meta( $post->ID, 'period_'.$day.'_' . $i, true ) === "period_" .$day . "_" . $i) { echo "checked"; } ?>>
            <label for="period_<?php echo $day; ?>_<?php echo $i; ?>">period <?php echo $i; ?></label>
            <br>
          <?php } ?>
        </div>
        <!-- container teaching days -->
      <?php } ?>

      <h3>Office Hours</h3>
      <?php foreach ($list_officeDays as $day) { ?>
        <!-- <p class="graph_days_officeHours"><?php echo ucfirst($day); ?></p> -->
        <div class="container_officeHours_inputs">
          <div class="container_group_timeslot">
            <h4><span id="large_officeDays_heading"><b><?php echo ucfirst($day); ?></b></span></h4>
            <div class="container_daily_input">
              <?php
              // create counting parameters via a list called gate
              // previously commented | using close port as gate ||
              // $gateList = array();
              for ($i = 1; $i < 4; $i++) {
                $gateList["appt_".$day."_".$i."_1"] = get_post_meta( $post->ID, 'appt_'.$day.'_' . $i . '_1', true );
              }

              $i_filled_times = 0;

              foreach ($gateList as $key => $value) {
                if (!empty($value)) {
                  $i_filled_times++;
                }
              }

              for ($i = 1; $i < 4; $i++) { ?>
                <div id="container_timeSlot_<?php echo $day ."_". $i; ?>">
                  <p>timeslot <?php echo $i; ?></p>
                  <table>
                    <tbody>
                      <tr>
                        <td>
                          <label for="appt_<?php echo $day; ?>_<?php echo $i; ?>_0"><?php echo $day; ?> start time</label>
                        </td>
                        <td>
                          <input type="time" id="appt_<?php echo $day; ?>_<?php echo $i; ?>_0" name="appt_<?php echo $day; ?>_<?php echo $i; ?>_0" value="<?php echo get_post_meta( $post->ID, 'appt_'.$day.'_' . $i . '_0', true ); ?>" placeholder="example: &quot;11 a.m.&quot;">
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <label for="appt_<?php echo $day; ?>_<?php echo $i; ?>_1"><?php echo $day; ?> end time</label>
                        </td>
                        <td>
                          <input type="time" id="appt_<?php echo $day; ?>_<?php echo $i; ?>_1" name="appt_<?php echo $day; ?>_<?php echo $i; ?>_1" value="<?php echo get_post_meta( $post->ID, 'appt_'.$day.'_' . $i . '_1', true ); ?>" placeholder="example: &quot;11 a.m.&quot;">

                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <!-- container showHide: timeslot_day_slotNumber -->
                <div id="container_unsetCheckboxes">
                  <input id="unsetLast<?php echo "_".$day."_".$i; ?>_start" type="checkbox" name="appt_<?php echo $day; ?>_<?php echo $i; ?>_0" value="">
                  <label for="unsetLast<?php echo "_".$day."_".$i; ?>_start">Unset Start Field <?php echo ucfirst($day) . " " . $i; ?></label>
                  <br>
                  <input id="unsetLast<?php echo "_".$day."_".$i; ?>_end" type="checkbox" name="appt_<?php echo $day; ?>_<?php echo $i; ?>_1" value="">
                  <label for="unsetLast<?php echo "_".$day."_".$i; ?>_end">Unset End Field <?php echo ucfirst($day) . " " . $i; ?></label>
                <br>
              </div>
              <!-- container_unsetCheckboxes -->
              <?php } ?>
              <!-- CSS: display none -->

              <?php for ($d = 1; $d < 4; $d++) { ?>
                <p class="clicker" id="trigger_<?php echo $day . "_" . $d; ?>">(+) add <?php if ($d == 1) { echo "new"; } ?> field</p>
              <?php } ?>

            </div>
            <!-- these are the pair of inputs being displayed via by table -->
          </div>
          <!-- these are the big blocks for individual days, overall container per day -->
        </div>
        <!-- container_officeHours_inputs -->
      <?php }

        /*
         leave these comments! for real.
         <!-- unset fields / inputs here -->
         <!-- unset fields / inputs here -->
         <!-- unset fields / inputs here -->
       */

      // create next key array
      // master list to hold the NEXT empty value

      $next_key = array();

      // format: [appt_wednesday_1_1] => 02:00
      foreach ($gateList as $key_day => $value_time) {
        if (empty($value_time)) {
          $explode = explode("_", $key_day);
          // [0] => appt
          // [1] => monday
          // [2] => 1,2,3
          // [3] => 1
          // container_timeSlot_ monday _ 0
          $container_key    = "container_timeSlot_" . $explode[1];  // container_timeSlot_monday
          $container_string = $container_key  . "_" . $explode[2];  // container_timeSlot_monday_1
          $container_value  = $explode[2];                          // 3
          // create TRIGGER
          $trigger_string   = "trigger_" . $explode[1]; // trigger_3

          // write only one KEY to be overwritten with latest VALUE
          if (!in_array($container_key, $next_key)) {
            $next_key[] = $container_key;
            $next_key[$container_key] = $container_value;
          }
        }
      } // end gate list explosion assignment

      // there's a little weirdness with the logic of saving the array above, so this is to clean the key=>value loop down to container=>identifier
      foreach ($next_key as $key_container => $value_nextTimeSlot) {
        if (strpos($key_container, "container_timeSlot") !== false) {
          foreach ($list_officeDays as $day) {
            if (strpos($key_container, $day) !== false) {
              $list_nextKey[$day] = $value_nextTimeSlot;
            }
          }
        }
      }

      // this could be done dynamically with a loop, but hard-coded because it's weird to think about looping i-- to count upward and then truncate at an interval -- it'd be better to loop if there were an unkown number of slots per day --
      $list_displayMap = array(
        "1" => array("3"),
        "2" => array("2","3"),
        "3" => array("1","2","3")
      );
      // [day]       =>  trigger  /  real  /  needed  ||  keys   | 4 - $i
      // [saturday]  =>  1                    1,2,3   ||         | 3
      foreach ($list_nextKey as $key_day => $value_trigger) {
        $i_loop = 4 - $value_trigger;
        foreach ($list_displayMap as $key_int => $value_values) {
          if ($key_int == $i_loop) {
            $master_containers[$key_day] = $value_values;
          }
        }
      }

      // Leave this comment
      // echo "<pre>";
      // 	print_r($master_containers);
      // echo "</pre>";

      // please consider removing this
      // please consider removing this
      // please consider removing this
      // please consider removing this
      ?>
      <script type="text/javascript">
        // load the script last to allow the fields to be counted. This was written for a previous version of the javascript, but it's still not a bad practice since the fields are being created through a loop. It just keeps them separated to ensure there's no mid-loop checking or asking for dependencies too early
        window.onload = function () {
          // hide all of the empty containers
          <?php
          foreach ($master_containers as $day => $list_slots) {
            $e = 0;
            foreach ($list_slots as $null_int => $slot) { ?>
              document.querySelector('#<?php echo "container_timeSlot_{$day}_{$slot}"; ?>').style.display = "none";
              <?php
              // show Triggers
              if ($e < 1) { ?>
                document.querySelector("#<?php echo "trigger_{$day}_{$slot}"; ?>").style.display   = "block";
                <?php $list_triggers["container_timeSlot_{$day}_{$slot}"] = "trigger_{$day}_{$slot}";
              }
              $e++;
            } // create trigger list
          } // loop master containers

          // show next container and hide trigger. One per page load
          foreach ($list_triggers as $container => $trigger) { ?>
              let click_<?php echo $trigger; ?> = document.querySelector("#<?php echo $trigger; ?>").onclick = function trigger_<?php echo $trigger; ?> () {
              document.querySelector('#<?php echo $container; ?>').style.display = "block";
              document.querySelector("#<?php echo $trigger; ?>").style.display   = "none";
            }
          <?php }	?>
        } // window.onload Master Function
      </script>
    </div>
    <!-- #container master schedule -->

  <?php }
  // end function
  // function member display info


  // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
  // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
  // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
  // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
  // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
  // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
  // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
  // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
  // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
  // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
  // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
  // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
  // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
  // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---

  // save meta box
  function myplugin_save_meta_box( $post_id ) {

    $is_autosave    = wp_is_post_autosave( $post_id );
    $is_revision    = wp_is_post_revision( $post_id );
    $is_valid_nonce = false;

    if ( isset( $_POST[ 'member_info_meta_box_nonce' ] ) ) {
      if ( wp_verify_nonce( $_POST[ 'member_info_meta_box_nonce' ], basename( __FILE__ ) ) ) {
        $is_valid_nonce = true;
      }
    }

    if ( $is_autosave || $is_revision || !$is_valid_nonce ) return;

    $member_meta['main_info']         = $_POST['main_info'];
    $member_meta['member-phone']      = esc_textarea($_POST['member-phone']);
    $member_meta['member-email']      = esc_textarea($_POST['member-email']);
    $member_meta['member-office']     = esc_textarea($_POST['member-office']);
    $member_meta['member-website']    = esc_textarea($_POST['member-website']);
    $member_meta['member-position']   = esc_textarea($_POST['member-position']);
    $member_meta['member-moreInfo']   = $_POST['member-moreInfo'];
    $member_meta['member-department'] = $_POST['member-department'];

    $list_officeDays = array("monday", "tuesday", "wednesday", "thursday", "friday", "saturday");

    // [teaching schedule]:
    foreach ($list_officeDays as $day) {
      for ($i = 1; $i < 11; $i++) {
        @$member_meta['period_'.$day.'_' . $i] = $_POST['period_'.$day.'_' . $i];
      }
    }

    // [/teaching schedule] |  [office hours | office hours open / start
    foreach ($list_officeDays as $day) {
      for ($i = 1; $i < 4; $i++) {
        @$member_meta['appt_'.$day.'_'.$i.'_0'] = $_POST['appt_'.$day.'_'.$i.'_0'];
      }
    }

    // office hours end / close
    foreach ($list_officeDays as $day) {
      for ($i = 1; $i < 4; $i++) {
        @$member_meta['appt_'.$day.'_'.$i.'_1'] = $_POST['appt_'.$day.'_'.$i.'_1'];
      }
    }

    // [/office hours]
    // unset options fields here from post
    foreach ($member_meta as $key => $value ) {
      // Don't store custom data twice
      // jump :: This has something to do with breaking when Unsetting values/fields
      // if ( 'revision' === $post->post_type ) {	return;}

      if ( get_post_meta( $post_id, $key, false ) ) {
        // If the custom field already has a value, update it.
        update_post_meta( $post_id, $key, $value );
      } else {
        // If the custom field doesn't have a value, add it.
        add_post_meta( $post_id, $key, $value);
      }

      if (!$value) {
        // Delete the meta key if there's no value
        delete_post_meta( $post_id, $key );
      }
    } // forLoop through list $member_meta

    if (!empty($_POST['main_info'])) {
      $data = $_POST['main_info'];
      update_post_meta($post_id, 'main_info', $data);
    }
  } // end function: Save Metabox ()

  add_action( 'save_post', 'myplugin_save_meta_box' );
