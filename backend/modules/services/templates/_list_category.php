<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Lib\Utils\Common;
use Bookly\Lib\Utils\DateTime;
use Bookly\Lib\Utils\Price;
use Bookly\Lib\Proxy;

$time_interval = get_option( 'bookly_gen_time_slot_length' );
?>
<?php if ( ! empty( $category_collection ) ) : ?>
	<div class="panel-group" id="categories_list" role="tablist" aria-multiselectable="true">
		<?php foreach ( $category_collection as $category ) : ?>
			<?php $category_id   = $category['id'];
			?>
			<div class="panel panel-default bookly-js-collapse" data-rank-id="<?php echo $category_id ?>">
				<div class="panel-heading" role="tab" id="s_<?php echo $category_id  ?>">
					<div class="row">
						<div class="col-sm-8 col-xs-10">
							<div class="bookly-flexbox">
								<div class="booskly-flex-cell bookly-vertical-middle" style="width: 1%">
									<i class="bookly-js-handle bookly-icon bookly-icon-draghandle bookly-margin-right-sm bookly-cursor-move"
									   title="<?php esc_attr_e( 'Reorder', 'bookly' ) ?>"></i>
								</div>
								<div class="bookly-flex-cell bookly-vertical-middle">
									<a role="button"
									   class="panel-title collapsed bookly-js-category-title"
									   data-toggle="collapse"
									   data-parent="#categories_list"
									   href="#category_<?php echo $category_id  ?>"
									   aria-expanded="false"
									   aria-controls="category_<?php echo $category_id  ?>">
										<?php echo esc_html( $category['name'] ) ?>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div id="category_<?php echo $category_id  ?>" class="panel-collapse collapse" role="tabpanel" style="height: 0">
					<div class="panel-body">
						<form method="post">
							<?php Proxy\Shared::renderCategoryFormHead( $category ) ?>
							
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label for="category_<?php echo $category_id  ?>"><?php _e( 'Main Category', 'bookly' ) ?></label>
										<select id="category_<?php echo $category_id  ?>" class="form-control" name="rank_id"><option value="0"><?php _e( 'Uncategorized', 'bookly' ) ?></option>
											<?php foreach ( $rank_collection as $rank ) : ?>
												<option value="<?php echo $rank['id'] ?>" <?php selected( $rank['id'], $category['rank_id'] ) ?>><?php echo esc_html( $rank['name'] ) ?></option>
											<?php endforeach ?>
										</select>
									</div>
								</div>
								
							</div>
							
							<?php Proxy\Shared::renderCategoryForm( $category ) ?>
							
							<div class="panel-footer">
								<input type="hidden" name="action" value="bookly_update_category">
								<input type="hidden" name="id" value="<?php echo esc_html( $category_id  ) ?>">
								<input type="hidden" name="update_staff" value="0">
								<span class="bookly-js-categories-error text-danger"></span>
								<?php Common::csrf() ?>
								<?php Common::submitButton( null, 'ajax-category-send' ) ?>
								<?php Common::resetButton( null, 'js-reset' ) ?>
							</div>
						</form>
					</div>
				</div>
			</div>
		<?php endforeach ?>
	</div>
<?php endif ?>
<div style="display: none">
	<?php Proxy\Shared::renderAfterCategoryList( $category_collection ) ?>
</div>