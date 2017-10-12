<?php
namespace Bookly\Backend\Modules\Services;

use Bookly\Lib;

/**
 * Class Controller
 * @package Bookly\Backend\Modules\Services
 */
class Controller extends Lib\Base\Controller
{
    const page_slug = 'bookly-services';
    const page_slug2 = 'bookly-categories';

    /**
     * Index page.
     */
    public function index()
    {
        wp_enqueue_media();
        $this->enqueueStyles( array(
            'wp'       => array( 'wp-color-picker' ),
            'frontend' => array( 'css/ladda.min.css' ),
            'backend'  => array( 'bootstrap/css/bootstrap-theme.min.css' ),
        ) );

        $this->enqueueScripts( array(
            'wp'       => array( 'wp-color-picker' ),
            'backend'  => array(
                'bootstrap/js/bootstrap.min.js' => array( 'jquery' ),
                'js/help.js'  => array( 'jquery' ),
                'js/alert.js' => array( 'jquery' ),
                'js/range_tools.js' => array( 'jquery' ),
            ),
            'module'   => array(
            	'js/service.js' => array(
	                'jquery-ui-sortable',
		            'jquery'
	            )
            ),
            'frontend' => array(
                'js/spin.min.js'  => array( 'jquery' ),
                'js/ladda.min.js' => array( 'bookly-spin.min.js', 'jquery' ),
            )
        ) );

        wp_localize_script( 'bookly-service.js', 'BooklyL10n', array(
            'csrf_token'            => Lib\Utils\Common::getCsrfToken(),
            'capacity_error'        => __( 'Min capacity should not be greater than max capacity.', 'bookly' ),
            'are_you_sure'          => __( 'Are you sure?', 'bookly' ),
            'service_special_day'   => Lib\Config::specialDaysEnabled() && Lib\Config::specialDaysEnabled()
        ) );

        // Allow add-ons to enqueue their assets.
        Lib\Proxy\Shared::enqueueAssetsForServices();

        $staff_collection    = $this->getStaffCollection();
        $category_collection = $this->getCategoryCollection();
        $service_collection  = $this->getServiceCollection();
        $this->render( 'index', compact( 'staff_collection', 'category_collection', 'service_collection' ) );
    }
	
	/**
	 * Categories page.
	 */
	public function categories()
	{
		wp_enqueue_media();
		$this->enqueueStyles( array(
			'wp'       => array( 'wp-color-picker' ),
			'frontend' => array( 'css/ladda.min.css' ),
			'backend'  => array( 'bootstrap/css/bootstrap-theme.min.css' ),
		) );
		
		$this->enqueueScripts( array(
			'wp'       => array( 'wp-color-picker' ),
			'backend'  => array(
				'bootstrap/js/bootstrap.min.js' => array( 'jquery' ),
				'js/help.js'  => array( 'jquery' ),
				'js/alert.js' => array( 'jquery' ),
				'js/range_tools.js' => array( 'jquery' ),
			),
			'module'   => array(
				'js/rank.js' => array(
					'jquery-ui-sortable',
					'jquery'
				),
			),
			'frontend' => array(
				'js/spin.min.js'  => array( 'jquery' ),
				'js/ladda.min.js' => array( 'bookly-spin.min.js', 'jquery' ),
			)
		) );
		
		wp_localize_script( 'bookly-service.js', 'BooklyL10n', array(
			'csrf_token'            => Lib\Utils\Common::getCsrfToken(),
			'capacity_error'        => __( 'Min capacity should not be greater than max capacity.', 'bookly' ),
			'are_you_sure'          => __( 'Are you sure?', 'bookly' ),
			'service_special_day'   => Lib\Config::specialDaysEnabled() && Lib\Config::specialDaysEnabled()
		) );
		
		// Allow add-ons to enqueue their assets.
		Lib\Proxy\Shared::enqueueAssetsForServices();
		
		$category_collection = $this->getCategoryCollection();
		$rank_collection     = $this->getRankCollection();
		$this->render( 'categories', compact( 'category_collection', 'rank_collection' ) );
	}
    
    /**
     *
     */
    public function executeGetCategoryServices()
    {
        wp_send_json_success( $this->render( '_list', $this->getCaSeStCollections(), false ) );
    }
	
	/**
	 *
	 */
	public function executeGetCategoryRanks()
	{
		wp_send_json_success( $this->render( '_list_category',  $this->getCaSeRkCollections(), false ) );
	}

    /**
     *
     */
    public function executeAddCategory()
    {
        $html = '';
        if ( ! empty ( $_POST ) ) {
            if ( $this->csrfTokenValid() ) {
                $form = new Forms\Category();
                $form->bind( $this->getPostParameters() );
                if ( $category = $form->save() ) {
                    $html = $this->render( '_category_item', array( 'category' => $category->getFields() ), false );
                }
            }
        }
	    wp_send_json_success( compact( 'html' ) );

    }
	
	/**
	 *
	 */
	public function executeAddRank()
	{
		$html = '';
		if ( ! empty ( $_POST ) ) {
			if ( $this->csrfTokenValid() ) {
				$form = new Forms\Rank();
				$form->bind( $this->getPostParameters() );
				if ( $rank = $form->save() ) {
					$html = $this->render( '_rank_item', array( 'rank' => $rank->getFields() ), false );
				}
			}
		}
		wp_send_json_success( compact( 'html' ) );

	}
	
    /**
     * Update category.
     */
    public function executeUpdateCategory()
    {
	    
        $form = new Forms\Category();
        $form->bind( $this->getPostParameters() );
	    $form->save();
	    if ( $category = $form->save() ) {
		    $alert = Lib\Proxy\Shared::updateCategory( array( 'success' => array( __( 'Settings saved.', 'bookly' ) ) ), $category, $this->getPostParameters() );
		    wp_send_json_success( Lib\Proxy\Shared::prepareUpdateCategoryResponse( compact( 'html', 'alert' ), $category, $this->getPostParameters() ) );
	    }
	   
    }
	
	/**
	 * Update rank.
	 */
	public function executeUpdateRank()
	{
		$form = new Forms\Rank();
		$form->bind( $this->getPostParameters() );
		$form->save();
	}
    
    /**
     * Update category position.
     */
    public function executeUpdateCategoryPosition()
    {
        $category_sorts = $this->getParameter( 'position' );
        foreach ( $category_sorts as $position => $category_id ) {
            $category_sort = new Lib\Entities\Category();
            $category_sort->load( $category_id );
            $category_sort->set( 'position', $position );
            $category_sort->save();
        }
    }
    
	/**
	 * Update rank position.
	 */
	public function executeUpdateRankPosition()
	{
		$rank_sorts = $this->getParameter( 'position' );
		foreach ( $rank_sorts as $position => $rank_id ) {
			$rank_sort = new Lib\Entities\Rank();
			$rank_sort->load( $rank_id );
			$rank_sort->set( 'position', $position );
			$rank_sort->save();
		}
	}
    
    /**
     * Update services position.
     */
    public function executeUpdateServicesPosition()
    {
        $services_sorts = $this->getParameter( 'position' );
        foreach ( $services_sorts as $position => $service_ids ) {
            $services_sort = new Lib\Entities\Service();
            $services_sort->load( $service_ids );
            $services_sort->set( 'position', $position );
            $services_sort->save();
        }
    }

    /**
     * Delete category.
     */
    public function executeDeleteCategory()
    {
        $category = new Lib\Entities\Category();
        $category->set( 'id', $this->getParameter( 'id', 0 ) );
        $category->delete();
    }
	
	/**
	 * Delete rank.
	 */
	public function executeDeleteRank()
	{
		$category = new Lib\Entities\Rank();
		$category->set( 'id', $this->getParameter( 'id', 0 ) );
		$category->delete();
	}
	
	public function executeAddService()
	{
		$form = new Forms\Service();
		$form->bind( $this->getPostParameters() );
		$form->getObject()->set( 'duration', Lib\Config::getTimeSlotLength() );
		$service = $form->save();
		$data = $this->getCaSeStCollections( $service->get( 'category_id' ) );
		Lib\Proxy\Shared::serviceCreated( $service, $this->getPostParameters() );
		wp_send_json_success( array( 'html' => $this->render( '_list', $data, false ), 'service_id' => $service->get( 'id' ) ) );
	}

    public function executeRemoveServices()
    {
        $service_ids = $this->getParameter( 'service_ids', array() );
        if ( is_array( $service_ids ) && ! empty ( $service_ids ) ) {
            foreach ( $service_ids as $service_id ) {
                Lib\Proxy\Shared::serviceDeleted( $service_id );
            }
            Lib\Entities\Service::query( 's' )->delete()->whereIn( 's.id', $service_ids )->execute();
        }
        wp_send_json_success();
    }
	


    /**
     * Update service parameters and assign staff
     */
    public function executeUpdateService()
    {
	    /** @var \wpdb $wpdb */
	    global $wpdb;
	
	    $form = new Forms\Service();
	    $form->bind( $this->getPostParameters() );
	    $service = $form->save();
	
	    $staff_ids = $this->getParameter( 'staff_ids', array() );
	    if ( empty ( $staff_ids ) ) {
		    Lib\Entities\StaffService::query()->delete()->where( 'service_id', $service->get( 'id' ) )->execute();
	    } else {
		    Lib\Entities\StaffService::query()->delete()->where( 'service_id', $service->get( 'id' ) )->whereNotIn( 'staff_id', $staff_ids )->execute();
		    if ( $this->getParameter( 'update_staff', false ) ) {
			    $wpdb->update(
				    Lib\Entities\StaffService::getTableName(),
				    array(
					    'price'        => $this->getParameter( 'price' ),
					    'capacity_min' => $this->getParameter( 'capacity_min' ),
					    'capacity_max' => $this->getParameter( 'capacity_max' ),
				    ),
				    array( 'service_id' => $this->getParameter( 'id' ) )
			    );
		    }
		    // Create records for newly linked staff.
		    $existing_staff_ids = array();
		    $res = Lib\Entities\StaffService::query()
		                                    ->select( 'staff_id' )
		                                    ->where( 'service_id', $service->get( 'id' ) )
		                                    ->fetchArray();
		    foreach ( $res as $staff ) {
			    $existing_staff_ids[] = $staff['staff_id'];
		    }
		    foreach ( $staff_ids as $staff_id ) {
			    if ( ! in_array( $staff_id, $existing_staff_ids ) ) {
				    $staff_service = new Lib\Entities\StaffService();
				    $staff_service->set( 'staff_id',     $staff_id );
				    $staff_service->set( 'service_id',   $service->get( 'id' ) );
				    $staff_service->set( 'price',        $service->get( 'price' ) );
				    $staff_service->set( 'capacity_min', $service->get( 'capacity_min' ) );
				    $staff_service->set( 'capacity_max', $service->get( 'capacity_max' ) );
				    $staff_service->save();
			    }
		    }
	    }
	
	    $alert = Lib\Proxy\Shared::updateService( array( 'success' => array( __( 'Settings saved.', 'bookly' ) ) ), $service, $this->getPostParameters() );
	
	    $price = Lib\Utils\Price::format( $service->get( 'price' ) );
	    $nice_duration = Lib\Utils\DateTime::secondsToInterval( $service->get( 'duration' ) );
	    $title = $service->get( 'title' );
	    $color = $service->get( 'color' );
	    wp_send_json_success( Lib\Proxy\Shared::prepareUpdateServiceResponse( compact( 'title', 'price', 'color', 'nice_duration', 'alert' ), $service, $this->getPostParameters() ) );
    }
	
	/**
	 * Array for rendering rank list.
	 *
	 * @param int $rank_id
	 * @return array
	 */
	private function getCaSeRkCollections( $rank_id = 0 )
	{
		if ( ! $rank_id ) {
			$rank_id = $this->getParameter( 'rank_id', 0 );
		}
		
		return array(
			'staff_collection'    => $this->getStaffCollection(),
			'category_collection' => $this->getCategoryCollection($rank_id),
		);
	}
	
	/**
	 * Array for rendering service list.
	 *
	 * @param int $category_id
	 * @return array
	 */
	private function getCaSeStCollections( $category_id = 0 )
	{
		if ( ! $category_id ) {
			$category_id = $this->getParameter( 'category_id', 0 );
		}
		
		return array(
			'service_collection'  => $this->getServiceCollection( $category_id ),
			'staff_collection'    => $this->getStaffCollection(),
			'category_collection' => $this->getCategoryCollection(),
		);
	}
	

    /**
     * @return array
     */
    private function getCategoryCollection( $id = 0 )
    {
	    $categories = Lib\Entities\Category::query( 's' )
		    ->select( 's.*' )
            ->whereRaw( 's.rank_id = %d OR !%d', array( $id, $id ) )
            ->groupBy( 's.id' )
            ->indexBy( 'id' )
            ->sortBy( 's.position' );
	
	    return $categories->fetchArray();
        
    }
	/**
	 * @return array
	 */
	private function getRankCollection()
	{
		return Lib\Entities\Rank::query()->sortBy( 'position' )->fetchArray();
	}
    /**
     * @return array
     */
    private function getStaffCollection()
    {
        return Lib\Entities\Staff::query()->fetchArray();
    }

    /**
     * @param int $id
     * @return array
     */
    private function getServiceCollection( $id = 0 )
    {
        $services = Lib\Entities\Service::query( 's' )
            ->select( 's.*, COUNT(staff.id) AS total_staff, GROUP_CONCAT(DISTINCT staff.id) AS staff_ids' )
            ->leftJoin( 'StaffService', 'ss', 'ss.service_id = s.id' )
            ->leftJoin( 'Staff', 'staff', 'staff.id = ss.staff_id' )
            ->whereRaw( 's.category_id = %d OR !%d', array( $id, $id ) )
            ->groupBy( 's.id' )
            ->indexBy( 'id' )
            ->sortBy( 's.position' );

        return $services->fetchArray();
    }

    public function executeUpdateExtraPosition()
    {
        Lib\Proxy\ServiceExtras::reorder( $this->getParameter( 'position' ) );
        wp_send_json_success();
    }
}