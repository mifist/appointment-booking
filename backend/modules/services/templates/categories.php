<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div id="bookly-tbs" class="wrap">
    <div class="bookly-tbs-body">
        <div class="page-header text-right clearfix">
            <div class="bookly-page-title">
                <?php _e( 'Categories', 'bookly' ) ?>
            </div>
            <?php \Bookly\Backend\Modules\Support\Components::getInstance()->renderButtons( $this::page_slug ) ?>
        </div>
        <div class="row">
	        <div id="bookly-rank" class="col-sm-4">
		
		        <div id="bookly-ranks-list" class="bookly-nav">
			        <div class="bookly-nav-item active bookly-rank-item bookly-js-all-ranks">
				        <div class="bookly-padding-vertical-xs"><?php _e( 'All Category', 'bookly' ) ?></div>
			        </div>
			        <ul id="bookly-rank-item-list">
				        <?php foreach ( $rank_collection as $rank ) $this->render( '_rank_item', compact( 'rank' ) ) ?>
			        </ul>
		        </div>
		        <div class="form-group">
			        <button id="bookly-new-rank" type="button"
			                class="btn btn-xlg btn-block btn-success-outline">
				        <i class="dashicons dashicons-plus-alt"></i>
				        <?php _e( 'New Main Category', 'bookly' ) ?>
			        </button>
		        </div>
		        <form method="post" id="new-rank-form" style="display: none">
			        <div class="form-group bookly-margin-bottom-md">
				        <div class="form-field form-required">
					        <label for="bookly-rank-name"><?php _e( 'Name', 'bookly' ) ?></label>
					        <input class="form-control" id="bookly-rank-name" type="text" name="name" />
					        <input type="hidden" name="action" value="bookly_add_rank" />
					        <?php \Bookly\Lib\Utils\Common::csrf() ?>
				        </div>
			        </div>
			
			        <hr />
			        <div class="text-right">
				        <button type="submit" class="btn btn-success">
					        <?php _e( 'Save', 'bookly' ) ?>
				        </button>
				        <button type="button" class="btn btn-default">
					        <?php _e( 'Cancel', 'bookly' ) ?>
				        </button>
			        </div>
		        </form>
	
	        </div>
	        <div id="bookly-ranks-wrapper" class="col-sm-8">
		        <div class="panel panel-default bookly-main">
			        <div class="panel-body">
				        <h4 class="bookly-block-head">
					        <span class="bookly-rank-title"><?php _e( 'All Sub Category', 'bookly' ) ?></span>
				        </h4>
				
				        <p class="bookly-margin-top-xlg no-result-rank" <?php if ( ! empty ( $rank_collection ) ) : ?>style="display: none;"<?php endif ?>>
					        <?php _e( 'No services found. Please add services.', 'bookly' ) ?>
				        </p>
				
				        <div class="bookly-margin-top-xlg" id="bookly-js-rank-list">
					        <?php include '_list_category.php' ?>
				        </div>
			        </div>
		        </div>
	        </div>
        </div>
	  
    </div>

    <div id="bookly-update-category-settings" class="modal fade" tabindex=-1 role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <div class="modal-title h2"><?php _e( 'Update service setting', 'bookly' ) ?></div>
                </div>
                <div class="modal-body">
                    <p><?php _e( 'You are about to change a service setting which is also configured separately for each staff member. Do you want to update it in staff settings too?', 'bookly' ) ?></p>
                    <div class="checkbox">
                        <label>
                            <input id="bookly-remember-my-choice" type="checkbox">
                            <?php _e( 'Remember my choice', 'bookly' ) ?>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-default bookly-no" data-dismiss="modal" aria-hidden="true">
                        <?php _e( 'No, update just here in services', 'bookly' ) ?>
                    </button>
                    <button type="submit" class="btn btn-success bookly-yes"><?php _e( 'Yes', 'bookly' ) ?></button>
                </div>
            </div>
        </div>
    </div>
	
	
</div>