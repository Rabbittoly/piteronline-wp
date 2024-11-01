<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'rb_weather_data' ) ) {
	/**
	 * @param $options
	 *
	 * @return false|string
	 */
	function rb_weather_data( $options ) {

		$title               = isset( $options['title'] ) ? $options['title'] : false;
		$location            = isset( $options['location'] ) ? $options['location'] : false;
		$api_key             = isset( $options['api_key'] ) ? $options['api_key'] : false;
		$days_to_show        = isset( $options['forecast_days'] ) ? $options['forecast_days'] : 5;
		$units               = ( isset( $options['units'] ) and strtoupper( $options['units'] ) === "C" ) ? "metric" : "imperial";
		$units_display       = ( $units === "metric" ) ? esc_html__( 'C', 'foxiz-core' ) : esc_html__( 'F', 'foxiz-core' );
		$locale              = 'en';
		$now_w_icon          = '';
		$ruby_today_temp     = '';
		$name_country        = '';
		$weather_description = '';

		$system_locale = get_locale();
		$foxizlocales  = [
			'en',
			'es',
			'sp',
			'fr',
			'it',
			'de',
			'pt',
			'ro',
			'pl',
			'ru',
			'uk',
			'ua',
			'fi',
			'nl',
			'bg',
			'sv',
			'se',
			'ca',
			'tr',
			'hr',
			'zh',
			'zh_tw',
			'zh_cn',
			'hu',
		];

		if ( in_array( $system_locale, $foxizlocales ) ) {
			$locale = $system_locale;
		}

		if ( in_array( substr( $system_locale, 0, 2 ), $foxizlocales ) ) {
			$locale = substr( $system_locale, 0, 2 );
		}

		if ( is_numeric( $location ) ) {
			$city_name_slug = $location;
			$your_city      = "id=" . $location;
		} else {
			$city_name_slug = sanitize_title( $location );
			$your_city      = "q=" . $location;
		}

		$weather_transient_name = 'foxiz_' . $city_name_slug . "_" . strtolower( $units_display ) . '_' . $locale;

		if ( get_transient( $weather_transient_name ) ) {
			$weather_data = get_transient( $weather_transient_name );
		} else {
			$weather_data['now'] = [];
			$now_ping            = "https://api.openweathermap.org/data/2.5/weather?" . $your_city . "&lang=" . $locale . "&units=" . $units . "&APPID=" . $api_key;
			$now_ping_get        = wp_remote_get( $now_ping, [ 'timeout' => 120 ] );

			if ( is_wp_error( $now_ping_get ) ) {
				weather_error();

				return false;
			}

			$city_data = json_decode( $now_ping_get['body'] );

			if ( isset( $city_data->cod ) && $city_data->cod === 404 ) {
				weather_error();

				return false;
			} else {
				$weather_data['now'] = $city_data;
			}

			if ( $days_to_show !== 'hide' ) {
				$weather_data['forecast'] = [];

				$rb_forecast_ping = "https://api.openweathermap.org/data/2.5/forecast?" . $your_city . "&lang=" . $locale . "&units=" . $units . "&cnt=35&appid=" . $api_key;

				$rb_forecast_ping_get = wp_remote_get( $rb_forecast_ping, [ 'timeout' => 120 ] );

				$rb_forecast_data = json_decode( $rb_forecast_ping_get['body'] );

				if ( isset( $rb_forecast_data->cod ) && $rb_forecast_data->cod === 404 ) {
					weather_error();

					return false;
				} else {
					$weather_data['forecast'] = $rb_forecast_data;
				}
			}
		}

		if ( $weather_data['now'] || $weather_data['forecast'] ) {
			set_transient( $weather_transient_name, $weather_data, 10800 );
		}

		$ruby_today = $weather_data['now'];

		if ( ! empty( $ruby_today->main->temp ) ) {
			$ruby_today_temp = round( $ruby_today->main->temp );
		}

		if ( ! empty( $ruby_today->main->temp_max ) ) {
			$ruby_today_high = round( $ruby_today->main->temp_max );
		}

		if ( ! empty( $ruby_today->main->temp_min ) ) {
			$ruby_today_low = round( $ruby_today->main->temp_min );
		}

		if ( ! empty( $ruby_today->main->humidity ) ) {
			$ruby_today->main->humidity = round( $ruby_today->main->humidity );
		}

		if ( ! empty( $ruby_today->wind->speed ) && 0 !== ( $ruby_today->wind->speed ) ) {
			$ruby_today->wind->speed = round( $ruby_today->wind->speed );
		}

		$speed_text = ( $units === "metric" ) ? esc_html__( 'km/h', 'foxiz-core' ) : esc_html__( 'mph', 'foxiz-core' );
		if ( ! empty( $ruby_today->name ) ) {
			$name_country = $ruby_today->name;
		}

		if ( ! empty( $ruby_today->weather[0]->description ) ) {
			$weather_description = $ruby_today->weather[0]->description;
		}

		if ( ! empty( $ruby_today->main->humidity ) ) {
			$weather_humidity = $ruby_today->main->humidity . '' . esc_html__( '%', 'foxiz-core' );
		}

		if ( ! empty( $ruby_today->wind->speed ) ) {
			$weather_speed = $ruby_today->wind->speed . ' ' . esc_html( $speed_text ) . ' ';
		}

		if ( ! empty( $ruby_today->weather[0]->icon ) ) {
			$ruby_today_icon = $ruby_today->weather[0]->icon;
			$now_w_icon      = rb_weather_icon( $ruby_today_icon );
		}

		ob_start();
		if ( ( 401 !== $weather_data['now']->cod ) && ! empty( $options['api_key'] ) ) :
			$classes = 'rb-weather-wrap';
			if ( ! empty( $options['color_scheme'] ) ) {
				$classes .= ' light-scheme';
			}
			?>
			<div class="<?php echo strip_tags( $classes ); ?>">
				<?php if ( ! empty( $title ) ) : ?>
					<div class="rb-w-title h4">
						<?php foxiz_render_inline_html( $title ) ?>
					</div>
				<?php endif; ?>
				<div class="rb-w-header">
					<div class="col-left">
						<div class="rb-w-big-icon">
							<?php echo Foxiz_SVG_Icons::get_weather_icon( $now_w_icon, $now_w_icon . '-current' ); ?>
						</div>
					</div>
					<div class="col-right">
						<div class="rb-w-units h6">
							<span><?php echo strip_tags( $ruby_today_temp ) ?></span><span class="ruby-degrees"><sup><?php echo '&deg;' . foxiz_strip_tags( $units_display ) ?></sup></span>
						</div>
					</div>
				</div>
				<div class="rb-w-stats">
					<div class="col-left">
						<div class="rb-header-name h6">
							<?php echo strip_tags( $name_country ) ?>
						</div>
						<div class="rb-w-desc">
							<?php echo strip_tags( $weather_description ) ?>
						</div>
					</div>
					<div class="col-right">
						<div class="rb-weather-highlow">
							<?php if ( ! empty( $ruby_today_high ) ) : ?>
								<span class="icon-high"><?php echo Foxiz_SVG_Icons::get_weather_icon( 'high' ); ?></span>
								<span class="text-high"><?php echo strip_tags( $ruby_today_high ) ?><sup><?php echo '&deg;'; ?></sup></span>
							<?php endif; ?>
							<?php if ( ! empty( $ruby_today_low ) ) : ?>
								<span>&lowbar;</span>
								<span class="icon-low"><?php echo Foxiz_SVG_Icons::get_weather_icon( 'low' ); ?></span>
								<span class="text-low"><?php echo strip_tags( $ruby_today_low ) ?><sup><?php echo '&deg;'; ?></sup></span>
							<?php endif; ?>
						</div>
						<div class="rb-w-humidity">
							<?php if ( ! empty( $weather_humidity ) ) : ?>
								<span class="icon-humidity"><?php echo Foxiz_SVG_Icons::get_weather_icon( 'raindrop' ); ?></span>
								<span><?php echo strip_tags( $weather_humidity ) ?></span>
							<?php endif; ?>
						</div>
						<div class="ruby-weather-wind">
							<?php if ( ! empty( $weather_speed ) ) : ?>
								<span class="icon-windy"><?php echo Foxiz_SVG_Icons::get_weather_icon( 'windy' ); ?></span>
								<span><?php echo strip_tags( $weather_speed ) ?></span>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<?php if ( $days_to_show !== 'hide' && ( ! empty( $weather_data['forecast'] ) || ! empty( $weather_data['forecast']->list ) ) ) : ?>
					<div class="w-forecast-wrap">
						<?php
						$rb_forecast_days = [];
						$today_date       = date( 'Ymd', current_time( 'timestamp', 0 ) );

						foreach ( (array) $weather_data['forecast']->list as $rb_forecast ) :

							$day_of_week = date( 'Ymd', $rb_forecast->dt );

							if ( $today_date > $day_of_week ) {
								continue;
							}

							if ( $today_date == $day_of_week ) {
								if ( ! empty( $rb_forecast->main->temp_max ) && $rb_forecast->main->temp_max > $ruby_today_high ) {
									$ruby_today_high = round( $rb_forecast->main->temp_max );
								}
								if ( ! empty( $rb_forecast->main->temp_min ) && $rb_forecast->main->temp_min < $ruby_today_low ) {
									$ruby_today_low = round( $rb_forecast->main->temp_min );
								}
							}

							if ( empty( $rb_forecast_days[ $day_of_week ] ) ) {
								$rb_forecast_days[ $day_of_week ] = [
									'utc'  => $rb_forecast->dt,
									'icon' => $rb_forecast->weather[0]->icon,
									'temp' => ! empty( $rb_forecast->main->temp_max ) ? round( $rb_forecast->main->temp_max ) : '',
								];
							} else {
								if ( ( $rb_forecast->main->temp_max ) > ( $rb_forecast_days[ $day_of_week ]['temp'] ) ) {
									$rb_forecast_days[ $day_of_week ]['temp'] = round( $rb_forecast->main->temp_max );
									$rb_forecast_days[ $day_of_week ]['icon'] = $rb_forecast->weather[0]->icon;
								}
							}
						endforeach;

						$count          = 1;
						foreach ( $rb_forecast_days as $rb_forecast_day ) :
							$forecast_icon = rb_weather_icon( $rb_forecast_day['icon'] );
							$rb_the_day = date_i18n( 'D', $rb_forecast_day['utc'] ); ?>
							<div class="w-forecast-day forecast-day-<?php echo strip_tags( $days_to_show ) ?>">
								<div class="w-forecast-day h6"><?php echo strip_tags( $rb_the_day ) ?></div>
								<div class="w-forecast-icon"><?php echo Foxiz_SVG_Icons::get_weather_icon( $forecast_icon, $forecast_icon . '-' . $count ) ?></div>
								<div class="w-forecast-temp"><?php echo strip_tags( $rb_forecast_day['temp'] ) ?>
									<sup><?php echo '&deg;' . strip_tags( $units_display ) ?></sup>
								</div>
							</div>
							<?php
							if ( $count === intval( $days_to_show ) ) {
								break;
							}
							$count ++;
						endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php else:
			weather_error();
		endif;

		return ob_get_clean();
	}
}

if ( ! function_exists( 'rb_weather_icon' ) ) {
	/**
	 * @param $rb_icon
	 *
	 * @return string
	 */
	function rb_weather_icon( $rb_icon ) {

		if ( $rb_icon === '01d' ) {
			$icon_weather = 'day-sunny';
		} elseif ( $rb_icon === '01n' ) {
			$icon_weather = 'moon-full';
		} elseif ( $rb_icon === '02d' ) {
			$icon_weather = 'day-cloudy';
		} elseif ( $rb_icon === '02n' ) {
			$icon_weather = 'night-cloudy';
		} elseif ( $rb_icon === '04d' || $rb_icon === '04n' ) {
			$icon_weather = 'cloudy';
		} elseif ( $rb_icon === '09d' || $rb_icon === '09n' ) {
			$icon_weather = 'rain';
		} elseif ( $rb_icon === '10d' ) {
			$icon_weather = 'day-rain';
		} elseif ( $rb_icon === '10n' ) {
			$icon_weather = 'night-rain';
		} elseif ( $rb_icon === '11d' ) {
			$icon_weather = 'storm-showers';
		} elseif ( $rb_icon === '11n' ) {
			$icon_weather = 'storm-showers';
		} elseif ( $rb_icon === '13d' ) {
			$icon_weather = 'day-snow';
		} elseif ( $rb_icon === '13n' ) {
			$icon_weather = 'night-alt-snow';
		} elseif ( $rb_icon === '50d' ) {
			$icon_weather = 'day-fog';
		} elseif ( $rb_icon === '50n' ) {
			$icon_weather = 'night-fog';
		} else {
			$icon_weather = 'cloudy';
		}

		return $icon_weather;
	}
}

if ( ! function_exists( 'weather_error' ) ) {
	function weather_error() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="rb-weather-error is-meta"><?php echo esc_html__( 'No weather information available. Please check your location here: ', 'foxiz-core' ) ?>
			<a target="_blank" href="https://openweathermap.org/find/"><?php echo esc_html__( 'Find location', 'foxiz-core' ) ?></a>
		</div>
		<?php
	}
}

if ( ! function_exists( 'rb_sidebar_banner' ) ) {
	/**
	 * @param $instance
	 */
	function rb_sidebar_banner( $instance ) {

		$inner_classes = 'w-banner-content';
		if ( ! empty( $instance['color_scheme'] ) ) {
			$inner_classes .= ' light-scheme';
		} ?>
		<div class="w-banner">
			<div class="banner-bg">
				<?php if ( ! empty( $instance['e_image']['id'] ) ) :
					$image = wp_get_attachment_image_src( $instance['e_image']['id'], 'full' );
					if ( empty( $image[0] ) ) {
						$image = [ '', 0, 0 ];
					}
					if ( ! empty( $instance['e_dark_image']['id'] ) ) :
						$dark_image = wp_get_attachment_image_src( $instance['e_dark_image']['id'], 'full' );
						if ( empty( $dark_image[0] ) ) {
							$dark_image = [ '', 0, 0 ];
						} ?>
						<img loading="lazy" decoding="async" data-mode="default" src="<?php echo esc_url( $image[0] ); ?>" alt="<?php echo esc_attr__( 'banner', 'foxiz-core' ); ?>" width="<?php echo strip_tags( $image[1] ); ?>" height="<?php echo strip_tags( $image[2] ); ?>"/>
						<img loading="lazy" decoding="async" data-mode="dark" src="<?php echo esc_url( $dark_image[0] ); ?>" alt="<?php echo esc_attr__( 'banner', 'foxiz-core' ); ?>" width="<?php echo strip_tags( $dark_image[1] ); ?>" height="<?php echo strip_tags( $dark_image[2] ); ?>"/>
					<?php else : ?>
						<img loading="lazy" decoding="async" src="<?php echo esc_url( $image[0] ); ?>" alt="<?php echo esc_attr__( 'banner', 'foxiz-core' ); ?>" width="<?php echo strip_tags( $image[1] ); ?>" height="<?php echo strip_tags( $image[2] ); ?>"/>
					<?php endif;
				else :
					if ( ! empty( $instance['image'] ) ) :
						$image_size = foxiz_get_image_size( $instance['image'] );
						if ( ! empty( $instance['dark_image'] ) ) :
							$dark_image_size = foxiz_get_image_size( $instance['dark_image'] );
							?>
							<img loading="lazy" decoding="async" data-mode="default" src="<?php echo esc_url( $instance['image'] ); ?>" alt="<?php echo esc_attr__( 'banner', 'foxiz-core' );
							?>" width="<?php if ( ! empty( $image_size[0] ) ) {
								echo strip_tags( $image_size[0] );
							} ?>" height="<?php if ( ! empty( $image_size[1] ) ) {
								echo strip_tags( $image_size[1] );
							} ?>">
							<img loading="lazy" decoding="async" data-mode="dark" src="<?php echo esc_url( $instance['dark_image'] ); ?>" alt="<?php echo esc_attr__( 'banner', 'foxiz-core' );
							?>" width="<?php if ( ! empty( $dark_image_size[0] ) ) {
								echo strip_tags( $dark_image_size[0] );
							} ?>" height="<?php if ( ! empty( $dark_image_size[1] ) ) {
								echo strip_tags( $dark_image_size[1] );
							} ?>">
						<?php else : ?>
							<img loading="lazy" decoding="async" src="<?php echo esc_url( $instance['image'] ); ?>" alt="<?php echo esc_attr__( 'banner', 'foxiz-core' );
							?>" width="<?php if ( ! empty( $image_size[0] ) ) {
								echo strip_tags( $image_size[0] );
							} ?>" height="<?php if ( ! empty( $image_size[1] ) ) {
								echo strip_tags( $image_size[1] );
							} ?>"/>
						<?php endif;
					endif;
				endif; ?>
			</div>
			<div class="<?php echo strip_tags( $inner_classes ); ?>">
				<div class="content-inner">
					<?php if ( ! empty( $instance['title'] ) ) : ?>
						<h5 class="w-banner-title h2"><?php foxiz_render_inline_html( $instance['title'] ); ?></h5>
					<?php endif;
					if ( ! empty( $instance['description'] ) ) : ?>
						<div class="w-banner-desc element-desc"><?php foxiz_render_inline_html( $instance['description'] ); ?></div>
					<?php endif;
					if ( ! empty( $instance['url'] ) ) : ?>
						<a class="banner-btn is-btn" href="<?php echo esc_url( $instance['url'] ) ?>" target="_blank" rel="noopener nofollow"><?php foxiz_render_inline_html( $instance['submit'] ) ?></a>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
	}
}


if ( ! function_exists( 'rb_get_covid_data' ) ) {
	/**
	 * @param string $country
	 *
	 * @return int[]|mixed
	 */
	function rb_get_covid_data( $country = 'all' ) {

		$data = get_transient( 'rb_covid_' . $country );

		if ( ! empty( $data['confirmed'] ) || ! empty( $data['deaths'] ) ) {
			return $data;
		} else {
			delete_transient( 'rb_covid_' . $country );
		}

		$data = [
			'confirmed' => 0,
			'deaths'    => 0,
		];

		$params = [
			'sslverify' => true,
			'timeout'   => 100,
		];

		if ( 'all' === $country ) {
			$response = wp_remote_get( 'https://disease.sh/v3/covid-19/all?yesterday=false&allowNull=true', $params );
		} else {
			$response = wp_remote_get( 'https://disease.sh/v3/covid-19/countries/' . strip_tags( $country ) . '?yesterday=true&strict=false', $params );
		}

		if ( ! is_wp_error( $response ) && isset( $response['response']['code'] ) && 200 === $response['response']['code'] ) {
			$response = json_decode( wp_remote_retrieve_body( $response ) );

			if ( ! empty( $response->cases ) ) {
				$data['confirmed'] = $response->cases;
			}
			if ( ! empty( $response->deaths ) ) {
				$data['deaths'] = $response->deaths;
			}
		} else {
			$data = [
				'confirmed' => - 1,
				'deaths'    => - 1,
			];
		}

		set_transient( 'rb_covid_' . $country, $data, 43200 );

		return $data;
	}
}

if ( ! function_exists( 'foxiz_render_covid_data' ) ) {
	/**
	 * @param $attrs
	 *
	 * @return false|string
	 */
	function foxiz_render_covid_data( $attrs ) {

		$settings = shortcode_atts( [
			'country_code'    => '',
			'country_name'    => '',
			'title_tag'       => '',
			'icon'            => '',
			'confirmed_label' => foxiz_html__( 'Confirmed', 'foxiz-core' ),
			'death_label'     => foxiz_html__( 'Death', 'foxiz-core' ),
			'confirmed'       => '',
			'deaths'          => '',
			'date'            => '1',
		], $attrs );

		if ( empty( $settings['country_code'] ) ) {
			$settings['country_code'] = 'all';
		}

		if ( empty( $settings['title_tag'] ) ) {
			$settings['title_tag'] = 'h3';
		}

		$data = rb_get_covid_data( trim( $settings['country_code'] ) );

		if ( - 1 === $data['confirmed'] ) {
			$data['confirmed'] = $settings['confirmed'];
			$data['deaths']    = $settings['deaths'];
		}

		ob_start();
		?>
		<div class="block-covid-data">
			<div class="data-inner">
				<?php if ( ! empty( $settings['country_name'] ) ) {
					echo '<div class="country-name"><' . strip_tags( $settings['title_tag'] ) . '>' . foxiz_strip_tags( $settings['country_name'] ) . '</' . strip_tags( $settings['title_tag'] ) . '></div>';
				} ?>
				<div class="data-item data-confirmed">
					<p class="description-text">
						<span class="data-item-icon"><?php foxiz_render_svg( 'chart' ); ?></span><?php foxiz_render_inline_html( $settings['confirmed_label'] ); ?>
					</p>
					<p class="data-item-value h5"><?php echo foxiz_pretty_number( $data['confirmed'] ); ?></p>
				</div>
				<div class="data-item data-death">
					<p class="description-text">
						<span class="data-item-icon"><?php foxiz_render_svg( 'chart' ); ?></span><?php foxiz_render_inline_html( $settings['death_label'] ); ?>
					</p>
					<p class="data-item-value h5"><?php echo foxiz_pretty_number( $data['deaths'] ); ?></p>
				</div>
				<?php if ( ! empty( $settings['icon'] ) ) {
					foxiz_render_svg( 'virus' );
				} ?>
			</div>
		</div>
		<?php return ob_get_clean();
	}
}

if ( ! function_exists( 'foxiz_render_pricing_plan' ) ) {
	/**
	 * @param $settings
	 *
	 * @return string
	 */
	function foxiz_render_pricing_plan( $settings ) {

		$output = '';

		if ( empty( $settings['box_style'] ) ) {
			$settings['box_style'] = 'shadow';
		}

		$classes = 'plan-box is-box-' . $settings['box_style'];
		if ( ! empty( $settings['color_scheme'] ) ) {
			$classes .= ' light-scheme';
		}

		$output .= '<div class="' . strip_tags( $classes ) . '"><div class="plan-inner">';
		$output .= '<div class="plan-header">';
		if ( ! empty( $settings['title'] ) ) {
			$output .= '<h2 class="plan-heading">' . foxiz_strip_tags( $settings['title'] ) . '</h2>';
		}
		if ( ! empty( $settings['description'] ) ) {
			$output .= '<p class="plan-description">' . foxiz_strip_tags( $settings['description'] ) . '</p>';
		}
		$output .= '</div>';

		if ( ! empty( $settings['price'] ) ) {
			$output .= '<div class="plan-price-wrap h6">';
			if ( ! empty( $settings['unit'] ) ) {
				$output .= '<span class="plan-price-unit">' . foxiz_strip_tags( $settings['unit'] ) . '</span>';
			}
			$output .= '<span class="plan-price">' . foxiz_strip_tags( $settings['price'] ) . '</span>';
			if ( ! empty( $settings['tenure'] ) ) {
				$output .= '<span class="plan-tenure">' . foxiz_strip_tags( $settings['tenure'] ) . '</span>';
			}
			$output .= '</div>';
		}

		if ( is_array( $settings['features'] ) ) {
			$output .= '<div class="plan-features">';
			foreach ( $settings['features'] as $feature ) {
				if ( ! empty( $feature['feature'] ) ) {
					$output .= '<span class="plan-feature">' . foxiz_strip_tags( $feature['feature'] ) . '</span>';
				}
			}
			$output .= '</div>';
		}
		$output .= '<div class="plan-button-wrap">';
		if ( ! empty( $settings['shortcode'] ) ) {
			$output .= do_shortcode( $settings['shortcode'] );
		} elseif ( ! empty( $settings['register_button'] ) && class_exists( 'SwpmSettings' ) ) {
			$output .= '<a class="button" href="' . SwpmSettings::get_instance()->get_value( 'registration-page-url' ) . '">' . foxiz_strip_tags( $settings['register_button'] ) . '</a>';
		}
		$output .= '</div>';

		$output .= '</div></div>';

		return $output;
	}
}