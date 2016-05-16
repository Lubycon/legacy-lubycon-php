<?php
     require_once '../database/database_class.php';

     $db = new Database();

?>

<section id="create_account_area" class="modal">
     <p id="account_title">Create An Account</p>
     <div id="account_box">
          <form id="account_idpass" name="form" action="./php/account/registration.php" method="post">
               <div id="account_inputs">
                    <div class="account_input_wrap">
                         <label>E-mail</label><input type="text" name="email" id="email_id" /><i></i>
                         <p id="email_id_check" class="form_check"></p>
                    </div>
                    <div class="account_input_wrap">
                         <label>Password</label><input type="password" name="pass" id="pass_id" /><i></i>
                         <p id="pass_id_check" class="form_check"></p>
                    </div>
                    <div class="account_input_wrap">
                         <label>Repeat Password</label><input type="password" name="repass" id="re_pass_id" /><i></i>
                         <p id="re_pass_id_check" class="form_check"></p>
                    </div>
                    <div class="account_input_wrap">
                         <label>Nickname</label><input type="text" name="nick" id="nick_id" /><i></i>
                         <p id="nick_id_check" class="form_check"></p>
                    </div>
                    <div class="account_input_wrap">
                         <label>Location</label>
                         <div class="location_option_ca">
                              <select class="locationFilter" name="country_code">
                                   <?php
                                        $db->query = "SELECT * FROM country";
                                        $db->askQuery();
                                        while($row = mysqli_fetch_array($db->result)){
                                             echo ("<option data-value = ".$row['countryCode'].">".$row['name']."</option>");
                                        }
                                   ?>
                              </select>
                         </div>
                    </div>
               </div>
               <div id="account_documents">
                    <p id="document_desc">Please read Terms of service and Private policy below before check.</p>
                    <div class="document_wrap">
                         <div class="check_zone">
                              <input type="checkbox" class="document_check_box" id="check1" name="terms_check"/>
                              <p>I read 
                                   <a href="./index.php?1=company&2=terms_of_service" class="document" target="_blank">
                                        Terms of service
                                   </a>
                                   and I agree it.
                              </p>  
                         </div>
                         <div class="check_zone">
                              <input type="checkbox" class="document_check_box" id="check2" name="private_check"/>
                              <p>I read
                                   <a href="./index.php?1=company&2=private_policy" class="document" target="_blank">
                                        Private policy
                                   </a>
                                   and I agree it.
                              </p>
                         </div>
                    </div>
               </div>  
               <p id="account_email_sendcheck">
                    <input type="checkbox" class="email_resive " id="check3" name="email_send_check"/>
                    Would like to receive newsletter by e-mail?
               </p>
               <input type="button" class="account_submit" value="Submit" disabled />
          </form>
    </div>
</section>