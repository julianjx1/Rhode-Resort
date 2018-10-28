<?php
namespace FullScreenMenuForElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 *  Full Screen Menu
 *
 * Elementor widget for Full Screen Menu.
 *
 * @since 1.0.0
 */
class Full_Screen_Menu extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'full-screen-menu-for-elementor';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Full Screen Menu', 'full-screen-menu-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-nav-menu';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'general-elements' ];
	}

	/**
	 * Retrieve the list of styles the widget depended on.
	 *
	 * Used to set styles dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget styles dependencies.
	 */
	public function get_style_depends() {
		return [ 'fsmfe-frontend' ];
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ ];
	}

	/**
	 * Get Saved Templates
	 *
	 * @since 1.0.2
	 *
	 * @access public
	 * 
	 * @return array
	 */
	public function get_saved_data( $type = 'page' ) {
		$templates = $this->get_post_template( $type );

		$options['0'] = __( 'Select Template', 'full-screen-menu-for-elementor' );

		if ( count( $templates ) ) {
			foreach ( $templates as $template ) {
				$options[ $template['id'] ] = $template['name'];
			}
		} else {
			$options['0'] = __( 'You haven\'t saved any templates yet.', 'full-screen-menu-for-elementor' );
		}
		return $options;
	}

	/**
	 * Get Templates based on category
	 *
	 * @since 1.0.2
	 * 
	 * @access public
	 * 
	 * @return array
	 */
	public function get_post_template( $type = 'page' ) {
		$posts = get_posts(
			array(
				'post_type' => 'elementor_library',
				'orderby' => 'title',
				'order' => 'ASC',
				'posts_per_page' => '-1',
				'tax_query' => array(
					array(
						'taxonomy' => 'elementor_library_type',
						'field' => 'slug',
						'terms' => $type,
					),
				),
			)
		);

		$templates = array();

		foreach ( $posts as $post ) {
			$templates[] = array(
				'id' => $post->ID,
				'name' => $post->post_title,
			);
		}

		return $templates;
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'content',
			[
				'label' => __( 'Layout', 'full-screen-menu-for-elementor' ),
			]
		);

		$this->add_control(
			'content_type',
			[
				'label' => __( 'Content Type', 'full-screen-menu-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'menu',
				'options' => [
					'menu' => __( 'Menu', 'full-screen-menu-for-elementor' ),
					'saved_section' => __( 'Saved Section', 'full-screen-menu-for-elementor' ),
					'saved_page' => __( 'Saved Page', 'full-screen-menu-for-elementor' ),
				],				
			]
		);

		$menus = $this->get_available_menus();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'menu',
				[
					'label' => __( 'Select Menu', 'full-screen-menu-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => $menus,
					'default' => array_keys( $menus )[0],
					'separator' => 'after',
					'description' => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'full-screen-menu-for-elementor' ), admin_url( 'nav-menus.php' ) ),
					'label_block' => 'true',
					'condition' => [
						'content_type' => 'menu'
					],
				]
			);
		} else {
			$this->add_control(
				'menu',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => sprintf( __( '<strong>There are no menus in your site.</strong><br>Go to the <a href="%s" target="_blank">Menus</a> screen to create one.', 'full-screen-menu-for-elementor' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'separator' => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
					'condition' => [
						'content_type' => 'menu'
					],
				]
			);
		}

		$this->add_control(
			'saved_section',
			[
				'label' => __( 'Select Section', 'full-screen-menu-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => '0',
				'separator' => 'after',
				'description' => sprintf( __( 'Go to the <a href="%s" target="_blank">My Templates</a> screen to manage your templates.', 'full-screen-menu-for-elementor' ), admin_url( 'edit.php?post_type=elementor_library' ) ),
				'options' => $this->get_saved_data( 'section' ),
				'label_block' => 'true',
				'condition' => [
					'content_type' => 'saved_section'
				],
			]
		);

		$this->add_control(
			'saved_page',
			[
				'label' => __( 'Select Page', 'full-screen-menu-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => '0',
				'separator' => 'after',
				'description' => sprintf( __( 'Go to the <a href="%s" target="_blank">My Templates</a> screen to manage your templates.', 'full-screen-menu-for-elementor' ), admin_url( 'edit.php?post_type=elementor_library' ) ),
				'options' => $this->get_saved_data( 'page' ),
				'label_block' => 'true',
				'condition' => [
					'content_type' => 'saved_page'
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'toggle',
			[
				'label' => __( 'Toggle Button', 'full-screen-menu-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_toggle_style' );

		$this->start_controls_tab(
			'tab_toggle_style_normal',
			[
				'label' => __( 'Normal', 'full-screen-menu-for-elementor' ),
			]
		);

		$this->add_control(
			'normal_toggle_color',
			[
				'label' => __( 'Color', 'full-screen-menu-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'separator' => 'none',
				'selectors' => [
					'{{WRAPPER}} .icon-bars .icon-bar' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_toggle_style_hover',
			[
				'label' => __( 'Hover', 'full-screen-menu-for-elementor' ),
			]
		);

		$this->add_control(
			'hover_toggle_color',
			[
				'label' => __( 'Color', 'full-screen-menu-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'separator' => 'none',
				'selectors' => [
					'{{WRAPPER}} .icon-bars:hover .icon-bar' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'toggle_align',
			[
				'label' => __( 'Toggle Align', 'full-screen-menu-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'full-screen-menu-for-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'full-screen-menu-for-elementor' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'full-screen-menu-for-elementor' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors_dictionary' => [
					'left' => 'margin-right: auto',
					'center' => 'margin: 0 auto',
					'right' => 'margin-left: auto',
				],
				'selectors' => [
					'{{WRAPPER}} .icon-bars' => '{{VALUE}}',
				],
			]
		);

		$this->add_control(
			'bars_size',
			[
				'label' => __( 'Size', 'full-screen-menu-for-elementor' ),
				'description' => __('Size of bars.', 'full-screen-menu-for-elementor'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 30,
				],
				'range' => [
					'px' => [
						'min' => 15,
						'max' => 100,
						'step' => 1,
					],
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .icon-bars' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'bars_space',
			[
				'label' => __( 'Space', 'full-screen-menu-for-elementor' ),
				'description' => __('Space between bars.', 'full-screen-menu-for-elementor'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 50,
						'step' => 1,
					],
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .icon-bars .bar-top' => 'margin-top: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .icon-bars .bar-bottom' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'bars_thickness',
			[
				'label' => __( 'Thickness', 'full-screen-menu-for-elementor' ),
				'description' => __('Thickness of bars.', 'full-screen-menu-for-elementor'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 2,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
						'step' => 1,
					],
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .icon-bars .icon-bar' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'overlay',
			[
				'label' => __( 'Overlay', 'full-screen-menu-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'overlay_background_color',
				'label' => __( 'Background', 'full-screen-menu-for-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .menu-overlay',
			]
		);

		$this->add_control(
			'overlay_padding',
			[
				'label' => __( 'Padding', 'full-screen-menu-for-elementor' ),
			   	'type' => Controls_Manager::DIMENSIONS,
			   	'size_units' => [ 'px', '%', 'em' ],
			   	'selectors' => [
					'{{WRAPPER}} .menu-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			   	],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'text',
			[
				'label' => __( 'Text', 'full-screen-menu-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'content_type' => 'menu',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_text_style' );

		$this->start_controls_tab(
			'tab_text_style_normal',
			[
				'label' => __( 'Normal', 'full-screen-menu-for-elementor' ),
			]
		);

		$this->add_control(
			'normal_text_color',
			[
				'label' => __( 'Text Color', 'full-screen-menu-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'separator' => 'none',
				'selectors' => [
					'{{WRAPPER}} .content-type-menu a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'normal_text_background_color',
			[
				'label' => __( 'Background Color', 'full-screen-menu-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'separator' => 'none',
				'selectors' => [
					'{{WRAPPER}} .content-type-menu a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_text_style_hover',
			[
				'label' => __( 'Hover', 'full-screen-menu-for-elementor' ),
			]
		);

		$this->add_control(
			'hover_text_color',
			[
				'label' => __( 'Text Color', 'full-screen-menu-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'separator' => 'none',
				'selectors' => [
					'{{WRAPPER}} .content-type-menu a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_text_background_color',
			[
				'label' => __( 'Background Color', 'full-screen-menu-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'separator' => 'none',
				'selectors' => [
					'{{WRAPPER}} .content-type-menu a:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'menu_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .content-type-menu a',
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label' => __( 'Text Align', 'full-screen-menu-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'full-screen-menu-for-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'full-screen-menu-for-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'full-screen-menu-for-elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .content-type-menu li' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Get available menus.
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function get_available_menus() {
		$menus = wp_get_nav_menus();

		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$available_menus = $this->get_available_menus();

		if ( ! $available_menus ) {
			return;
		}

		$settings = $this->get_active_settings();

		$content_type = $settings['content_type'];

		$this->add_render_attribute( 'menu-content', [
			'class' => 'content-type-' . $content_type,
		] );

		switch ( $content_type ) {
			case 'menu':
				$args = [
					'echo' => false,
					'menu' => $settings['menu'],
					'menu_class' => 'content-type-menu',
					'menu_id' => '',
					'fallback_cb' => '__return_empty_string',
					'container' => '',
				];
		
				$content_html = wp_nav_menu( $args );
				break;

			case 'saved_section':
				$content_html = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings['saved_section'] );
				break;

			case 'saved_page':
				$content_html = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings['saved_page'] );
				break;

			default:
				return;
				break;
		}

		$uniqid = uniqid();
		$this->add_render_attribute( 'menu-toggle', [
			'id' => 'menu-toggle-' . $uniqid,
			'class' => 'menu-toggle',
		] );
		$this->add_render_attribute( 'icon-bars', [
			'for' => 'menu-toggle-' . $uniqid,
			'class' => 'icon-bars',
		] );

		?>
		<div class="fsmfe-wrapper">
			<input type="checkbox" <?php echo $this->get_render_attribute_string( 'menu-toggle' ); ?>>
			<label <?php echo $this->get_render_attribute_string( 'icon-bars' ); ?>>
				<span class="icon-bar bar-top"></span>
				<span class="icon-bar bar-middle"></span>
				<span class="icon-bar bar-bottom"></span>
			</label>
			<div class="menu-overlay">
				<div <?php echo $this->get_render_attribute_string( 'menu-content' ); ?>>
					<?php echo $content_html; ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _content_template() {}

}
