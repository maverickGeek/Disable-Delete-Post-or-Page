/*!
 * Disable delete post or page admin scripts
 * @company Reactive Development LLC
 * @author Jeremy Selph <jselph@reactivedevelopment.net>
 * @link http://www.reactivedevelopment.net/
 * @version 0.1
 */ /**

    Change Log

     * 0.1  initial development by, Jeremy Selph                                                        2019/01/14
 
 */ /**
     * variables
     * @author Jeremy Selph <jselph@reactivedevelopment.net>
     * @version 1.4
     * @since 0.1
    */
   var ajaxUrl = rdl.ajax_url,
        ajaxNounce = rdl.ajaxNounce,
        msg = rdl.msg,
        post_id = rdl.post_id;

    /**
     * jquery onload
     * @author Jeremy Selph <jselph@reactivedevelopment.net>
     * @uses jquery.min.js
     * @version 0.2
     * @since 0.1
    */
    jQuery( document ).ready(function() {

        /**
         * when the star is clicked we need to update the users status
         * @author Jeremy Selph <jselph@reactivedevelopment.net>
         * @since 0.1
        */
        jQuery( "#jsRemoveDeleteLink" ).click( function() {                            
                        
            var is_checked = "no";
            if ( jQuery( "#jsRemoveDeleteLink" ).attr( "checked" ) ){ is_checked = "yes"; }                            
            
            jQuery.post( ajaxurl, 
                { action : "rdlRemove", nounce : ajaxNounce, post : post_id, checked : is_checked }, 
                function( response ){
                    
                    if ( response == "yes" ){
                        
                        jQuery( "#delete-action" ).hide( function() {
                            
                            var add = "<div id=\"js-remove-delete-message\" style=\"position:absolute; bottom:11px;\">" + msg +"</div>";
                            jQuery( add ).prependTo( "#major-publishing-actions" );

                        });

                    } else {
                        
                        jQuery( "#js-remove-delete-message" ).remove(); 
                        jQuery( "#delete-action" ).show(); 

                    } 

                }
            ); 

        });

    });