<?php

require __DIR__ . '/vendor/autoload.php';
$ig      = new \InstagramAPI\Instagram();
$climate = new \League\CLImate\CLImate();
$climate->green()->bold(
	"
 _                                      _
| |                                    | |
| |__  _   _ _ __   ___ _ ____   _____ | |_ ___ _ __
| '_ \| | | | '_ \ / _ \ '__\ \ / / _ \| __/ _ \ '__|
| | | | |_| | |_) |  __/ |   \ V / (_) | ||  __/ |
|_| |_|\__, | .__/ \___|_|    \_/ \___/ \__\___|_|
        __/ | | v1.9.0
       |___/|_| COPYRIGHT SGB TEAM - 2022"
);
output_clean( '' );
$climate->green()->bold( 'Hypervoter Terminal By 3XCODE' );
$climate->green( '© Decoded by 3XCODE (https://3xcode.com)' );
output_clean( '' );
run( $ig, $climate );

/**
 * Let's start the show
 */
function run( $ig, $climate ) {

    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', '-1');
    
	$poll_active = $slider_active = $question_active = $quiz_active = false;

	try {
		$climate->out( 'Masukin Username untuk config file lo sob xixixi...' );
		$config_name = getVarFromUser( 'Username' );
		if ( empty( $config_name ) ) {
			do {
				$config_name = getVarFromUser( 'Username' );
			} while ( empty( $config_name ) );
		}
		$climate->infoBold( 'Sabarin sob lagi proses xixixi...' );
		sleep( 1 );
		if ( file_exists( './config/config-' . $config_name . '.json' ) ) {
			$climate->infoBold( 'File config ketemu, bentar sob...' );
			sleep( 1 );
				$mafile = fopen( './config/config-' . $config_name . '.json', 'r' );
				$file   = fread( $mafile, filesize( './config/config-' . $config_name . '.json' ) );
				$data   = json_decode( $file );
				fclose( $mafile );
				$climate->infoBold( 'Cek license key dulu sob...' );
				sleep( 1 );
			if ( $data->license_key !== '' ) {
				$license_status = activate_license( $data->license_key, $ig );
			} else {
				$climate->backgroundRedWhiteBold( 'null' );
				sleep( 1 );
				exit();
			}

			if ( 'valid' === $license_status ) {
				$climate->backgroundBlueWhiteBold( '  License key aktif, lanjut sob...  ' );
				sleep( 1 );
				if ( '' !== $data->username ) {
					$climate->infoBold( 'Username lo ketemu sob' );
					$login = $data->username;
				} else {
					$climate->backgroundRedWhiteBold( 'Usernamenya kok gabisa sob?' );
					sleep( 1 );
					exit();
				}
				if ( '' !== $data->password ) {
					$climate->infoBold( 'Password lo ketemu sob' );
					$password = $data->password;
				} else {
					$climate->backgroundRedWhiteBold( 'Passwordnya kok gabisa sob?' );
					sleep( 1 );
					exit();
				}
				if ( '3' === $data->proxy ) {
					$climate->infoBold( 'Skip proxy nih sob? xixi sabar sob lanjut nih ye ...' );
					sleep( 1 );
				} else {
					$climate->infoBold( 'Make proxy sob? Bentar cek dulu...' );
					sleep( 1 );
					$validate_proxy = isValidProxy( $data->proxy, $climate );
					$climate->infoBold( 'Status proxy lo : ' . $validate_proxy );

					if ( $validate_proxy == 200 ) {
						$climate->info( 'Proxy sabi sob xixi, lanjut ...' );
						$ig->setProxy( $data->proxy );
					} else {
						$climate->info( 'Gabisa konek proxy sob, skip dah...' );
					}
				}

				if ( $data->speed_value ) {
					$climate->infoBold( 'Setting speed dulu sob ... ' );
					$speed = (int) $data->speed_value;

					if ( $speed > 1500000 ) {
						do {
							$climate->errorBold( 'Speed apaan lo isi? isi antara 10 000 sampe 500 000' );
							usleep( 500000 );
							$climate->errorBold( 'Untuk performa maximal kek ngews isi "0"...' );
							$speed = (int) getVarFromUser( 'Speed' );
						} while ( $speed > 1500000 );
					}

					if ( $speed === 0 ) {
						$climate->infoBold( ' Speed maxium sob? mangstap (awas rawan).  ' );
						$delay = 46;
					} else {
						$climate->infoBold( 'Speednya ' . $speed . ' stories/hari.' );
						$delay = round( 60 * 60 * 24 * 10 / $speed );
					}
				}

				if ( $data->fresh_stories_range > 0 ) {
					$fresh_stories_range = $data->fresh_stories_range;
					$climate->infoBold( 'Cek (Fresh Stories) dulu sob xixix, sabarin ...' );
					sleep( 1 );
					if ( $fresh_stories_range > 23 ) {
						do {
							$climage->errorBold( 'Fresh stories nya salah sob. isi antara 1 sampe 23. jam' );
							$climage->errorBold( 'pilih 0 buat skip.' );
							$fresh_stories_range = (int) getVarFromUser( 'Fresh Stories Range' );
						} while ( $fresh_stories_range > 23 );
					}
					$climate->infoBold( 'Fresh Stories Range nya ' . $fresh_stories_range );
					sleep( 1 );
				} else {
					$fresh_stories_range = 0;
					$climate->infoBold( 'Skip fresh story sob? oke lanjut' );
					usleep( 500000 );
				}

				$defined_targets = $data->targets;
				$poll_active     = $data->is_poll_vote_active;
				$slider_active   = $data->is_slider_points_active;
				$question_active = $data->is_questions_answers_active;
				$quiz_active     = $data->is_quiz_answers_active;

			} else {
				$climate->backgroundRedWhiteBold( '  License key nya udah ga valid, coba beli sana atau nunggu update lagi ya ga? (https://hypervoter.com)  ' );
				sleep( 1 );
				exit();
			}
		} else {
			sleep( 5 );
			$defined_targets = null;
		

			

			$climate->out( 'Isi data login lo sob.' );

			$login = getVarFromUser( 'Login' );
			if ( empty( $login ) ) {
				do {
					$login = getVarFromUser( 'Login' );
				} while ( empty( $login ) );
			}

			$password = getVarFromUser( 'Password' );
			if ( empty( $password ) ) {
				do {
					$password = getVarFromUser( 'Password' );
				} while ( empty( $password ) );
			}

			$first_loop = true;
			do {
				if ( $first_loop ) {
					$climate->out( "(Optional) set proxy sob biar aman aman club akun lo." );
					$climate->out( 'isinya bisa:' );
					$climate->out( 'http://ip:port atau http://username:password@ip:port' );
					$climate->out( "jangan pake https://." );
					$climate->out( "isi 3 kalo mau skip dan ga make proxy sob." );
					$first_loop = false;
				} else {
					$climate->out( 'Proxy lo salah sob' );
					$climate->out( 'coba cek lagi' );
				}

				$proxy = getVarFromUser( 'Proxy' );

				if ( empty( $proxy ) ) {
					do {
						$proxy = getVarFromUser( 'Proxy' );
					} while ( empty( $proxy ) );
				}

				if ( $proxy == '3' ) {
					// Skip proxy setup
					break;
				}
			} while ( ! isValidProxy( $proxy, $climate ) );

			$proxy_check = isValidProxy( $proxy, $climate );
			if ( $proxy == '3' ) {
				$climate->info( 'Proxynya skip dulu ya sob' );
			} elseif ( $proxy_check == 500 ) {
				$climate->info( 'Proxy lo salah. Skip sob' );
			} else {
				$climate->info( 'Proxy lo ketemu nih' );
				$ig->setProxy( $proxy );
			}

			$climate->out( 'Isi speednya dulu sob mau berapa nih?' );
			$climate->out( 'pilih antara 1 sampe 1 500 000 stories/hari atau isi 0 untuk kecepatan maximal.' );
			$climate->out( 'Kita rekomen sih isi 400 000 aja biar aman.' );
			$climate->out( 'Kalo lo isi maximal speed alias 0 ati ati ya takutnya limit sob.' );
			$climate->out( 'trus kalo lo make module lain speednya kurangin sob.' );
			$speed = (int) getVarFromUser( 'Speed' );

			if ( $speed > 1500000 ) {
				do {
					$climate->out( 'Salah sob, isi antara 1 sampe 1 500 000 stories/hari.' );
					$climate->out( 'isi 0 untuk performa maximal xixixi.' );
					$speed = (int) getVarFromUser( 'Delay' );
				} while ( $speed > 1500000 );
			}

			if ( $speed == 0 ) {
				$climate->out( 'Kecepatan penuhs sob, gas teroosss.' );
				$delay = 46;
			} else {
				$climate->out( 'Speed set to ' . $speed . ' stories/day.' );
				$delay = round( 60 * 60 * 24 * 10 / $speed );
			}

			$climate->out( 'Vote fresh story yang baru diupload antara 1-23 jam.' );
			$climate->out( 'X - isi antara 1 sampe 23.' );
			$climate->out( 'isi 0 kalo mau skip.' );
			$climate->out( 'bakal ngurangin speed sih, tapi hasilnya bakal lebih mangstap.' );
			$fresh_stories_range = (int) getVarFromUser( 'X' );

			if ( $fresh_stories_range > 23 ) {
				do {
					$climate->out( 'isianya salah sob, isi antara 1 sampe 23.' );
					$climate->out( 'isi 0 kalo lo mau skip.' );
					$fresh_stories_range = (int) getVarFromUser( 'X' );
				} while ( $fresh_stories_range > 23 );
			}

			$q_answers = (int) getVarFromUser( 'Jawab Question ga? isi 1 buat iya atau 0 enggak(0/1)' );
			$q_vote    = (int) getVarFromUser( 'Jawab Polls ga? isi 1 buat iya atau 0 enggak (0/1)' );
			$q_slide   = (int) getVarFromUser( 'Jawab Slide ga? isi 1 buat iya atau 0 enggak (0/1)' );
			$q_quiz    = (int) getVarFromUser( 'Jawab Quiz ga? isi 1 buat iya atau 0 enggak (0/1)' );
			$q_quiz    = (int) getVarFromUser( 'aktifin Mass View ga? isi 1 buat iya atau 0 enggak (0/1)' );

			if ( $q_answers !== 0 ) {
				$q_answers_a = getVarFromUser( 'Isi buat jawabnya question sob, pisahin pake koma ya sob' );
			}
			if ( $q_slide !== 0 ) {
				$q_slide_pointsMin = (int) getVarFromUser( 'Minimal slide kasih berapa sob? isi 0 sampe 100 (0/100)' );
				$q_slide_pointsMax = (int) getVarFromUser( 'Maximalnya? isi 0 sampe 100 (0/100)' );
			}

			if ( ! empty( $q_answers_a ) ) {
				$qs = explode( ',', $q_answers_a );
			} else {
				$qs = array();
			}

			$datas = json_encode(
				array(
					'is_poll_vote_active'         => ( $q_vote === 0 ) ? false : true,
					'is_slider_points_active'     => ( $q_slide === 0 ) ? false : true,
					'is_questions_answers_active' => ( $q_answers === 0 ) ? false : true,
					'is_quiz_answers_active'      => ( $q_quiz === 0 ) ? false : true,
					'questions_answers'           => $qs,
					'slider_points_range'         => array(
						( $q_slide_pointsMin ) ? $q_slide_pointsMin : 0,
						( $q_slide_pointsMax ) ? $q_slide_pointsMax : 100,

					),

				)
			);

			$data = json_decode( $datas );

		}

		$is_connected       = false;
		$is_connected_count = 0;
		$fail_message       = "Koneksi lo gagal, coba lagi nanti sob.";

		do {
			if ( $is_connected_count == 10 ) {
				if ( $e->getResponse() ) {
					$climate->errorBold( $e->getMessage() );
				}
				throw new Exception( $fail_message );
			}

			try {
				if ( $is_connected_count == 0 ) {
					$climate->infoBold( 'Mulai sobbb xixixi sabar loading dulu...' );
				}
				$login_resp = $ig->login( $login, $password );

				if ( $login_resp !== null && $login_resp->isTwoFactorRequired() ) {
					// Default verification method is phone
					$twofa_method = '1';

					// Detect is Authentification app verification is available
					$is_totp = json_decode( json_encode( $login_resp ), true );
					if ( $is_totp['two_factor_info']['totp_two_factor_on'] == '1' ) {
						$climate->infoBold( 'Yah kena Two-Factor sob coba cek ig lo' );
						$twofa_id     = $login_resp->getTwoFactorInfo()->getTwoFactorIdentifier();
						$twofa_method = '3';
					} else {
						$climate->bold(
							'isi code Two-factor dari nomor hp lo sob',
							$login_resp->getTwoFactorInfo()->getObfuscatedPhoneNumber()
						);
						$twofa_id = $login_resp->getTwoFactorInfo()->getTwoFactorIdentifier();
					}

					$twofa_code = getVarFromUser( 'Two-factor code' );

					if ( empty( $twofa_code ) ) {
						do {
							$twofa_code = getVarFromUser( 'Two-factor code' );
						} while ( empty( $twofa_code ) );
					}

					$is_connected       = false;
					$is_connected_count = 0;
					do {
						if ( $is_connected_count == 10 ) {
							if ( $e->getResponse() ) {
								$climate->errorBold( $e->getMessage() );
							}
							throw new Exception( $fail_message );
						}

						if ( $is_connected_count == 0 ) {
							$climate->infoBold( 'autentikasi Two-factor lagi proses sob sabarin...' );
						}

						try {
							$twofa_resp   = $ig->finishTwoFactorLogin( $login, $password, $twofa_id, $twofa_code, $twofa_method );
							$is_connected = true;
						} catch ( \InstagramAPI\Exception\NetworkException $e ) {
							sleep( 7 );
						} catch ( \InstagramAPI\Exception\EmptyResponseException $e ) {
							sleep( 7 );
						} catch ( \InstagramAPI\Exception\InvalidSmsCodeException $e ) {
							$is_code_correct = false;
							$is_connected    = true;
							do {
								$climate->errorBold( 'Kode lo salah sob, coba lagi dah.' );
								$twofa_code = getVarFromUser( 'Two-factor code' );

								if ( empty( $twofa_code ) ) {
									do {
										$twofa_code = getVarFromUser( 'Security code' );
									} while ( empty( $twofa_code ) );
								}

								$is_connected       = false;
								$is_connected_count = 0;
								do {
									try {
										if ( $is_connected_count == 10 ) {
											if ( $e->getResponse() ) {
												output( $e->getMessage() );
											}
											throw new Exception( $fail_message );
										}

										if ( $is_connected_count == 0 ) {
											$climate->infoBold( 'Lagi proses Verification sabarin sob...' );
										}
										$twofa_resp      = $ig->finishTwoFactorLogin( $login, $password, $twofa_id, $twofa_code, $twofa_method );
										$is_code_correct = true;
										$is_connected    = true;
									} catch ( \InstagramAPI\Exception\NetworkException $e ) {
										sleep( 7 );
									} catch ( \InstagramAPI\Exception\EmptyResponseException $e ) {
										sleep( 7 );
									} catch ( \InstagramAPI\Exception\InvalidSmsCodeException $e ) {
										$is_code_correct = false;
										$is_connected    = true;
									} catch ( \Exception $e ) {
										throw $e;
									}
									$is_connected_count += 1;
								} while ( ! $is_connected );
							} while ( ! $is_code_correct );
						} catch ( \Exception $e ) {
							throw $e;
						}

						$is_connected_count += 1;
					} while ( ! $is_connected );
				}

				$is_connected = true;
			} catch ( \InstagramAPI\Exception\NetworkException $e ) {
				sleep( 7 );
			} catch ( \InstagramAPI\Exception\EmptyResponseException $e ) {
				sleep( 7 );
			} catch ( \InstagramAPI\Exception\CheckpointRequiredException $e ) {
				throw new Exception( 'Please go to Instagram website or mobile app and pass checkpoint!' );
			} catch ( \InstagramAPI\Exception\ChallengeRequiredException $e ) {

				if ( ! ( $ig instanceof InstagramAPI\Instagram ) ) {
					throw new Exception( 'Oops! Something went wrong. Please try again later! (invalid_instagram_client)' );
				}

				if ( ! ( $e instanceof InstagramAPI\Exception\ChallengeRequiredException ) ) {
					throw new Exception( 'Oops! Something went wrong. Please try again later! (unexpected_exception)' );
				}

				if ( ! $e->hasResponse() || ! $e->getResponse()->isChallenge() ) {
					throw new Exception( 'Oops! Something went wrong. Please try again later! (unexpected_exception_response)' );
				}

				$challenge = $e->getResponse()->getChallenge();

				if ( is_array( $challenge ) ) {
					$api_path = $challenge['api_path'];
				} else {
					$api_path = $challenge->getApiPath();
				}

				$climate->info( 'Mau Kirim kode lewat mana sob.' );
				$climate->info( 'isi  1  2  atau  3' );
				$climate->infoBold( '1 - [Email]' );
				$climate->infoBold( '2 - [SMS]' );
				$climate->infoBold( '3 - [Exit]' );

				$choice = getVarFromUser( 'Choice' );

				if ( empty( $choice ) ) {
					do {
						$choice = getVarFromUser( 'Choice' );
					} while ( empty( $choice ) );
				}

				if ( $choice == '1' || $choice == '2' || $choice == '3' ) {
					// All fine
				} else {
					$is_choice_ok = false;
					do {
						$climate->errorBold( 'jawaban lo salah. isi 1, 2 atau 3.' );
						$choice = getVarFromUser( 'Choice' );

						if ( empty( $choice ) ) {
							do {
								$choice = getVarFromUser( 'Choice' );
							} while ( empty( $choice ) );
						}

						if ( $confirm == '1' || $confirm == '2' || $confirm == '3' ) {
							$is_choice_ok = true;
						}
					} while ( ! $is_choice_ok );
				}

				$challange_choice = 0;
				if ( $choice == '3' ) {
					run( $ig, $climate );
				} elseif ( $choice == '1' ) {
					// Email
					$challange_choice = 1;
				} else {
					// SMS
					$challange_choice = 0;
				}

				$is_connected       = false;
				$is_connected_count = 0;
				do {
					if ( $is_connected_count == 10 ) {
						if ( $e->getResponse() ) {
							$climate->errorBold( $e->getMessage() );
						}
						throw new Exception( $fail_message );
					}

					try {
						$challenge_resp = $ig->sendChallangeCode( $api_path, $challange_choice );

						// Failed to send challenge code via email. Try with SMS.
						if ( $challenge_resp->status != 'ok' ) {
							$challange_choice = 0;
							sleep( 7 );
							$challenge_resp = $ig->sendChallangeCode( $api_path, $challange_choice );
						}

						$is_connected = true;
					} catch ( \InstagramAPI\Exception\NetworkException $e ) {
						sleep( 7 );
					} catch ( \InstagramAPI\Exception\EmptyResponseException $e ) {
						sleep( 7 );
					} catch ( \Exception $e ) {
						throw $e;
					}

					$is_connected_count += 1;
				} while ( ! $is_connected );

				if ( $challenge_resp->status != 'ok' ) {
					if ( isset( $challenge_resp->message ) ) {
						if ( $challenge_resp->message == 'This field is required.' ) {
							$climate->info( "lah kok 'This field is required.'. Pasti gara gara ini sob" );
							$climate->info( 'pertama, Instagram udah ngirim kode. coba lo masukin sob.' );
							$climate->info( 'kedua. Instagram minta verifikasi nomor hp. coba cek instagram dari web atau hp lo.' );
						}
					} else {
						$climate->info( 'Instagram Response: ' . json_encode( $challenge_resp ) );
						$climate->info( "gabisa ngirim kode, coba lagi." );
						$climate->info( '- udah lo setting nomor hp belom?' );
						$climate->info( '- kalo engga, berarti lo harus setting nomor hp dulu.' );
						throw new Exception( '- Sometimes Instagram can force you to phone verification challenge process.' );
					}
				}

				if ( isset( $challenge_resp->step_data->contact_point ) ) {
					$contact_point = $challenge_resp->step_data->contact_point;
					if ( $choice == 2 ) {
						$climate->info( 'Masukin kode yang di kirim ke nomor dengan akhiran ' . $contact_point . '.' );
					} else {
						$climate->info( 'Masukin kode email dari email lo sob ' . $contact_point . '.' );
					}
				}

				$security_code = getVarFromUser( 'Security code' );

				if ( empty( $security_code ) ) {
					do {
						$security_code = getVarFromUser( 'Security code' );
					} while ( empty( $security_code ) );
				}

				if ( $security_code == '3' ) {
					throw new Exception( 'Reset in progress...' );
				}

				// Verification challenge
				$ig = challange( $ig, $login, $password, $api_path, $security_code, $proxy, $climate );

			} catch ( \InstagramAPI\Exception\AccountDisabledException $e ) {
				throw new Exception( 'Your account has been disabled for violating Instagram terms. Go Instagram website or mobile app to learn how you may be able to restore your account.' );
			} catch ( \InstagramAPI\Exception\ConsentRequiredException $e ) {
				throw new Exception( 'Instagram updated Terms and Data Policy. Please go to Instagram website or mobile app to review these changes and accept them.' );
			} catch ( \InstagramAPI\Exception\SentryBlockException $e ) {
				throw new Exception( 'Access to Instagram API restricted for spam behavior or otherwise abusing. You can try to use Session Catcher script (available by https://nextpost.tech/session-catcher) to get valid Instagram session from location, where your account created from.' );
			} catch ( \InstagramAPI\Exception\IncorrectPasswordException $e ) {
				throw new Exception( 'The password you entered is incorrect. Please try again.' );
			} catch ( \InstagramAPI\Exception\InvalidUserException $e ) {
				throw new Exception( "The username you entered doesn't appear to belong to an account. Please check your username in config file and try again." );
			} catch ( \Exception $e ) {
				throw $e;
			}

			$is_connected_count += 1;
		} while ( ! $is_connected );

		$climate->infoBold( 'Login pake @' . $login . ' berhasil sob.' );

		$data_targ = define_targets( $ig, $defined_targets, $climate );

		hypervote_v1( $data, $data_targ, $ig, $delay, $fresh_stories_range, $climate );

	} catch ( \Exception $e ) {
		$climate->errorBold( $e->getMessage() );
		sleep( 1 );
		$climate->errorBold( 'coba jalanin ulang scriptnya sob.' );
		exit;
	}
}

/**
 * Define targets for Hypervote
 */
function define_targets( $ig, $defined_targets = null, $climate ) {

	do {
		if ( null === $defined_targets ) {
			$climate->out( 'Targetnya siapa nih? coba isi yang bener.' );
			$climate->out( "masukin usernamenya gausah pake '@' ya sob." );
			$climate->out( 'contoh: tunkuas, awkarin, jessnolimit' );
			$targets_input = getVarFromUser( 'Usernames' );

			if ( empty( $targets_input ) ) {
				do {
					$targets_input = getVarFromUser( 'Usernames' );
				} while ( empty( $targets_input ) );
			}
		} else {
			$climate->infoBold( 'Sabar sob loading. xixixi...' );
			sleep( 1 );
			$targets_input = $defined_targets;
		}

		$targets_input = str_replace( ' ', '', $targets_input );
		$targets       = [];
		$targets       = explode( ',', trim( $targets_input ) );
		$targets       = array_unique( $targets );

		$pks              = [];
		$filtered_targets = [];
		foreach ( $targets as $target ) {

			$is_connected       = false;
			$is_connected_count = 0;
			do {
				if ( $is_connected_count == 10 ) {
					if ( $e->getResponse() ) {
						$climate->errorBold( $e->getMessage() );
					}
					$fail_message = "coba lagi sob, gagal nih";
					$climate->errorBold( $fail_message );
					run( $ig, $climate );
				}

				try {
					$user_resp = $ig->people->getUserIdForName( $target );
					$climate->info( '@' . $target . ' - [OK]' );
					$filtered_targets[] = $target;
					$pks[]              = $user_resp;
					$is_connected       = true;
					if ( ( $target != $targets[ count( $targets ) - 1 ] ) && ( count( $targets ) > 0 ) ) {
						sleep( 1 );
					}
				} catch ( \InstagramAPI\Exception\NetworkException $e ) {
					sleep( 7 );
				} catch ( \InstagramAPI\Exception\EmptyResponseException $e ) {
					sleep( 7 );
				} catch ( \InstagramAPI\Exception\ChallengeRequiredException $e ) {
					$climate->error( 'Please login again and pass verification challenge. Instagram will send you a security code to verify your identity.' );
					run( $ig, $climate );
				} catch ( \InstagramAPI\Exception\CheckpointRequiredException $e ) {
					$climate->error( 'Please go to Instagram website or mobile app and pass checkpoint!' );
					run( $ig, $climate );
				} catch ( \InstagramAPI\Exception\AccountDisabledException $e ) {
					$climate->error( 'Your account has been disabled for violating Instagram terms. Go Instagram website or mobile app to learn how you may be able to restore your account.' );
					$climate->error( 'Use this form for recovery your account: https://help.instagram.com/contact/1652567838289083' );
					run( $ig, $climate );
				} catch ( \InstagramAPI\Exception\ConsentRequiredException $e ) {
					$climate->error( 'Instagram updated Terms and Data Policy. Please go to Instagram website or mobile app to review these changes and accept them.' );
					run( $ig, $climate );
				} catch ( \InstagramAPI\Exception\SentryBlockException $e ) {
					$climate->error( 'Access to Instagram API restricted for spam behavior or otherwise abusing. You can try to use Session Catcher script (available by https://nextpost.tech/session-catcher) to get valid Instagram session from location, where your account created from.' );
					run( $ig, $climate );
				} catch ( \InstagramAPI\Exception\ThrottledException $e ) {
					$climate->error( 'Throttled by Instagram because of too many API requests.' );
					$climate->error( 'Please login again after 1 hour. You reached Instagram limits.' );
					run( $ig, $climate );
				} catch ( \InstagramAPI\Exception\NotFoundException $e ) {
					$is_connected        = true;
					$is_username_correct = false;
					do {
						$climate->error( 'Instagram profile username @' . $target . ' is incorrect or maybe user just blocked you (Login to Instagram website or mobile app and check that).' );
						$climate->error( 'Type 3 for skip this target.' );
						$target_new = getVarFromUser( 'Please provide valid username' );

						if ( empty( $target_new ) ) {
							do {
								$target_new = getVarFromUser( 'Please provide valid username' );
							} while ( empty( $target_new ) );
						}

						if ( $target_new == '3' ) {
							break;
						} else {
							$is_connected       = false;
							$is_connected_count = 0;
							do {
								if ( $is_connected_count == 10 ) {
									if ( $e->getResponse() ) {
										$climate->errorBold( $e->getMessage() );
									}
									$fail_message = "There is a problem with your Ethernet connection or Instagram is down at the moment. We couldn't establish connection with Instagram 10 times. Please try again later.";
									$climate->errorBold( $fail_message );
									run( $ig, $climate );
								}

								try {
									$user_resp = $ig->people->getUserIdForName( $target_new );
									$climate->info( '@' . $target_new . ' - [OK]' );
									$filtered_targets[]  = $target_new;
									$pks[]               = $user_resp;
									$is_username_correct = true;
									$is_connected        = true;
								} catch ( \InstagramAPI\Exception\NetworkException $e ) {
									sleep( 7 );
								} catch ( \InstagramAPI\Exception\EmptyResponseException $e ) {
									sleep( 7 );
								} catch ( InstagramAPI\Exception\NotFoundException $e ) {
									$is_username_correct = false;
									$is_connected        = true;
								} catch ( \Exception $e ) {
									$climate->error( $e->getMessage() );
									run( $ig, $climate );
								}
								$is_connected_count += 1;
							} while ( ! $is_connected );
						}
					} while ( ! $is_username_correct );
				} catch ( Exception $e ) {
					$climate->errorBold( $e->getMessage() );
					run( $ig, $climate );
				}

				$is_connected_count += 1;
			} while ( ! $is_connected );
		}
	} while ( empty( $filtered_targets ) );

	$targets = array_unique( $filtered_targets );
	$pks     = array_unique( $pks );

	$data_targ = [];
	for ( $i = 0; $i < count( $targets ); $i++ ) {
		$data_targ[ $i ] = [
			'username' => $targets[ $i ],
			'pk'       => $pks[ $i ],
		];
	}

	$climate->info( 'Dipilih: ' . count( $targets ) . ' targets: @' . implode( ', @', $targets ) . '.' );
	$climate->info( 'Bener nih target nya?' );
	$climate->info( '1 - [Yes]' );
	$climate->info( '2 - [No]' );
	$climate->info( '3 - [Exit]' );

	$confirm = getVarFromUser( 'Choice' );

	if ( empty( $confirm ) ) {
		do {
			$confirm = getVarFromUser( 'Choice' );
		} while ( empty( $confirm ) );
	}

	if ( $confirm == '1' || $confirm == '2' || $confirm == '3' ) {
		// All fine
	} else {
		$is_choice_ok = false;
		do {
			$climate->error( 'Jawab yang bener. isi 1, 2 atau 3.' );
			$confirm = getVarFromUser( 'Choice' );

			if ( empty( $confirm ) ) {
				do {
					$confirm = getVarFromUser( 'Choice' );
				} while ( empty( $confirm ) );
			}

			if ( $confirm == '1' || $confirm == '2' || $confirm == '3' ) {
				$is_choice_ok = true;
			}
		} while ( ! $is_choice_ok );
	}

	if ( $confirm == '3' ) {
		run( $ig, $climate );
	} elseif ( $confirm == '2' ) {
		$data_targ = define_targets( $ig, $defined_targets, $climate );
	} else {
		// All fine. Going to Hypervote.
	}

	return $data_targ;
}

/**
 * Get varable from user
 */
function getVarFromUser( $text ) {
	echo $text . ': ';
	$var = trim( fgets( STDIN ) );
	return $var;
}

/**
 * Output message with data to console
 */
function output( $message ) {
	echo '[', date( 'H:i:s' ), '] ', $message, PHP_EOL;
}

/**
 * Output clean message to console
 */
function output_clean( $message ) {
	echo $message, PHP_EOL;
}

/**
 * Validates proxy address
 */
function isValidProxy( $proxy, $climate ) {
	$climate->info( 'Connecting to Instagram...' );
	$code = null;

	try {
		$client       = new \GuzzleHttp\Client();
		$res          = $client->request(
			'GET',
			'http://www.instagram.com',
			[
				'timeout' => 60,
				'proxy'   => $proxy,
			]
		);
		$code         = $res->getStatusCode();
		$is_connected = true;
	} catch ( \Exception $e ) {
		//$climate->error( $e->getMessage() );
		$code = '500';
		//return false;
	}

	return $code;
}

/**
 * Validates proxy address
 */
function finishLogin( $ig, $login, $password, $proxy, $climate ) {
	$is_connected       = false;
	$is_connected_count = 0;

	try {
		do {
			if ( $is_connected_count == 10 ) {
				if ( $e->getResponse() ) {
					$climate->out( $e->getMessage() );
				}
				$fail_message = "There is a problem with your Ethernet connection or Instagram is down at the moment. We couldn't establish connection with Instagram 10 times. Please try again later.";
				$climate->errorBold( $fail_message );
				run( $ig, $climate );
			}

			if ( $proxy == '3' ) {
				// Skip proxy setup
			} else {
				$ig->setProxy( $proxy );
			}

			try {
				$login_resp = $ig->login( $login, $password );

				if ( $login_resp !== null && $login_resp->isTwoFactorRequired() ) {
					// Default verification method is phone
					$twofa_method = '1';

					// Detect is Authentification app verification is available
					$is_totp = json_decode( json_encode( $login_resp ), true );
					if ( $is_totp['two_factor_info']['totp_two_factor_on'] == '1' ) {
						$climate->info( 'Two-factor authentication required, please enter the code from you Authentication app' );
						$twofa_id     = $login_resp->getTwoFactorInfo()->getTwoFactorIdentifier();
						$twofa_method = '3';
					} else {
						$climate->info(
							'Two-factor authentication required, please enter the code sent to your number ending in %s',
							$login_resp->getTwoFactorInfo()->getObfuscatedPhoneNumber()
						);
						$twofa_id = $login_resp->getTwoFactorInfo()->getTwoFactorIdentifier();
					}

					$twofa_code = getVarFromUser( 'Two-factor code' );

					if ( empty( $twofa_code ) ) {
						do {
							$twofa_code = getVarFromUser( 'Two-factor code' );
						} while ( empty( $twofa_code ) );
					}

					$is_connected       = false;
					$is_connected_count = 0;
					do {
						if ( $is_connected_count == 10 ) {
							if ( $e->getResponse() ) {
								$climate->errorBold( $e->getMessage() );
							}
							$climate->errorBold( $fail_message );
							run( $ig, $climate );
						}

						if ( $is_connected_count == 0 ) {
							$climate->info( 'Two-factor authentication in progress...' );
						}

						try {
							$twofa_resp   = $ig->finishTwoFactorLogin( $login, $password, $twofa_id, $twofa_code, $twofa_method );
							$is_connected = true;
						} catch ( \InstagramAPI\Exception\NetworkException $e ) {
							sleep( 7 );
						} catch ( \InstagramAPI\Exception\EmptyResponseException $e ) {
							sleep( 7 );
						} catch ( \InstagramAPI\Exception\InvalidSmsCodeException $e ) {
							$is_code_correct = false;
							$is_connected    = true;
							do {
								$cliate->errorBold( 'Code is incorrect. Please check the syntax and try again.' );
								$twofa_code = getVarFromUser( 'Two-factor code' );

								if ( empty( $twofa_code ) ) {
									do {
										$twofa_code = getVarFromUser( 'Security code' );
									} while ( empty( $twofa_code ) );
								}

								$is_connected       = false;
								$is_connected_count = 0;
								do {
									try {
										if ( $is_connected_count == 10 ) {
											if ( $e->getResponse() ) {
												$climate->error( $e->getMessage() );
											}
											$climate->errorBold( $fail_message );
											run( $ig, $climate );
										}

										if ( $is_connected_count == 0 ) {
											$climate->info( 'Verification in progress...' );
										}
										$twofa_resp      = $ig->finishTwoFactorLogin( $login, $password, $twofa_id, $twofa_code, $twofa_method );
										$is_code_correct = true;
										$is_connected    = true;
									} catch ( \InstagramAPI\Exception\NetworkException $e ) {
										sleep( 7 );
									} catch ( \InstagramAPI\Exception\EmptyResponseException $e ) {
										sleep( 7 );
									} catch ( \InstagramAPI\Exception\InvalidSmsCodeException $e ) {
										$is_code_correct = false;
										$is_connected    = true;
									} catch ( \Exception $e ) {
										throw new $e();
									}
									$is_connected_count += 1;
								} while ( ! $is_connected );
							} while ( ! $is_code_correct );
						} catch ( \Exception $e ) {
							throw $e;
						}

						$is_connected_count += 1;
					} while ( ! $is_connected );
				}

				$is_connected = true;
			} catch ( \InstagramAPI\Exception\NetworkException $e ) {
				sleep( 7 );
			} catch ( \InstagramAPI\Exception\EmptyResponseException $e ) {
				sleep( 7 );
			} catch ( \InstagramAPI\Exception\CheckpointRequiredException $e ) {
				throw new Exception( 'Please go to Instagram website or mobile app and pass checkpoint!' );
			} catch ( \InstagramAPI\Exception\ChallengeRequiredException $e ) {
				$climate->error( 'Instagram Response: ' . json_encode( $e->getResponse() ) );
				$climate->error( "Couldn't complete the verification challenge. Please try again later." );
				throw new Exception( 'Developer code: Challenge loop.' );
			} catch ( \Exception $e ) {
				throw $e;
			}

			$is_connected_count += 1;
		} while ( ! $is_connected );
	} catch ( \Exception $e ) {
		$climate->errorBold( $e->getMessage() );
		run( $ig, $climate );
	}

	return $ig;
}

/**
 * Verification challenge
 */
function challange( $ig, $login, $password, $api_path, $security_code, $proxy, $climate ) {
	$is_connected       = false;
	$is_connected_count = 0;
	$fail_message       = "There is a problem with your Ethernet connection or Instagram is down at the moment. We couldn't establish connection with Instagram 10 times. Please try again later.";

	do {
		if ( $is_connected_count == 10 ) {
			if ( $e->getResponse() ) {
				$climate->errorBold( $e->getMessage() );
			}
			throw new Exception( $fail_message );
		}

		if ( $is_connected_count == 0 ) {
			$climate->info( 'Verification in progress...' );
		}

		try {
			$challenge_resp = $ig->finishChallengeLogin( $login, $password, $api_path, $security_code );
			$is_connected   = true;
		} catch ( \InstagramAPI\Exception\NetworkException $e ) {
			sleep( 7 );
		} catch ( \InstagramAPI\Exception\EmptyResponseException $e ) {
			sleep( 7 );
		} catch ( \InstagramAPI\Exception\InstagramException $e ) {

			$msg = $e->getMessage();
			$climate->out( $msg );

			$climate->out( 'Type 3 - to exit.' );

			$security_code = getVarFromUser( 'Security code' );

			if ( empty( $security_code ) ) {
				do {
					$security_code = getVarFromUser( 'Security code' );
				} while ( empty( $security_code ) );
			}

			if ( $security_code == '3' ) {
				throw new Exception( 'Reset in progress...' );
			}
		} catch ( \Exception $e ) {
			$msg = $e->getMessage();
			if ( $msg == 'Invalid Login Response at finishChallengeLogin().' ) {
				sleep( 7 );
				$ig           = finishLogin( $ig, $login, $password, $proxy, $climate );
				$is_connected = true;
			} else {
				throw $e;
			}
		}

		$is_connected_count += 1;
	} while ( ! $is_connected );

	return $ig;
}

/**
 * Hypervote loop - Algorithm #2
 */
function hypervote_v1( $data, $data_targ, $ig, $delay, $fresh_stories_range, $climate ) {

	$view_count          = 0;
	$st_count            = 0;
	$st_count_seen       = 0;
	$begin               = strtotime( date( 'Y-m-d H:i:s' ) );
	$begin_ms            = strtotime( date( 'Y-m-d H:i:s' ) );
	$begin_f             = strtotime( date( 'Y-m-d H:i:s' ) );
	$speed               = 0;
	$delitel             = 0;
	$counter1            = 0;
	$counter2            = 0;
	$stories             = [];
	$mycount             = 0;
	$poll_votes_count    = 0;
	$slider_points_count = 0;

	$climate->infoBold( 'Hypervote loop started.' );

	$targets = [];
	$targets = $data_targ;

	for ( $i = 0; $i < count( $data_targ ); $i++ ) {
		$data_targ[ $i ] += [
			'rank_token'  => \InstagramAPI\Signatures::generateUUID(),
			'users_count' => 0,
			'max_id'      => null,
			'begin_gf'    => null,
		];
	}

	do {
		foreach ( $data_targ as $key => $d ) {
			try {
				if ( $d['max_id'] == null ) {
					$is_gf_first = 1;
				}

				if ( ! empty( $d['begin_gf'] ) ) {
					$current_time = strtotime( date( 'Y-m-d H:i:s' ) );
					if ( ( $current_time - $d['begin_gf'] ) < 7 ) {
						$sleep_time = 7 - ( $current_time - $d['begin_gf'] );
						sleep( $sleep_time );
					}
				}

				try {
					$data_targ[ $key ]['begin_gf'] = strtotime( date( 'Y-m-d H:i:s' ) );
					$followers                     = $ig->people->getFollowers( $d['pk'], $d['rank_token'], null, $d['max_id'] );
					sleep(10);
				} catch (\InstagramAPI\Exception\ThrottledException $e) {
						$get_from_follow=false;
				} catch ( \InstagramAPI\Exception\NotFoundException $e ) {
					$climate->error( '@' . $d['username'] . ' not found or maybe user blocked you (login to Instagram website or mobile app and check that).' );
					unset( $data_targ[ $key ] );
					continue;
				} catch ( Exception $e ) {
					throw $e;
				}

				// DEBUG - BEGIN
				//$followers_json = json_decode($followers);
				// output(var_dump($followers_json));
				// exit;
				// DEBUG - END

				if ( empty( $followers->getUsers() ) ) {
					$climate->error( '@' . $d['username'] . " don't have any follower." );
					unset( $data_targ[ $key ] );
					continue;
				}

				$follos = json_decode( $followers, true );

				//output(print_r($follos['users'],true));

				$data_targ[ $key ]['max_id'] = $follos['next_max_id'];

				$followers_ids = [];
				foreach ( $follos['users'] as $follower ) {
					// Skip private accounts, accounts with anonymous profile picture and verified accounts
					// Check is user have stories at scrapping
					/*if ((1 !==$follower['is_private'])
					&& !($follower['has_anonymous_profile_picture'])
					&& !($follower['is_verified'])
					&& (0 !== $follower['latest_reel_media'])) {
						if (isset($fresh_stories_range)) {
							$fresh_stories_min = date("Y-m-d H:i:s", time() - (int)$fresh_stories_range*60*60);
							if ($follower['latest_reel_media'] >= strtotime($fresh_stories_min)) {
								$followers_ids[] = $follower['pk'];
							}
						} else {*/
							// Check is latest
							$followers_ids[] = $follower['pk'];
						/*}*/
					/*}*/
				}

				// Re-indexing array
				$followers_ids = array_values( $followers_ids );

				$data_targ[ $key ]['users_count'] = $d['users_count'] + count( $followers_ids );

				$number = count( $followers_ids );

				if ( $is_gf_first ) {
					$climate->info( $number . ' Get followers from @' . $d['username'] . ' Collected.' );
					$is_gf_first = 0;
				} else {
					$climate->info( $number . ' followers of @' . $d['username'] . ' Collected.' . 'Total: ' . number_format( $data_targ[ $key ]['users_count'], 0, '.', ' ' ) . 'followers has parsed' );
				}
				$index_new = 0;
				$index_old = 0;

				do {
					$index_new += 13;

					if ( ! isset( $followers_ids[ $index_new ] ) ) {
						do {
							$index_new -= 1;
						} while ( ! isset( $followers_ids[ $index_new ] ) );
					}

					if ( $index_new < $index_old ) {
						break;
					}

					$ids = [];
					for ( $i = $index_old; $i <= $index_new; $i++ ) {
						if ( isset( $followers_ids[ $i ] ) ) {
							$ids[] = $followers_ids[ $i ];
						}
					}

					try {
						try {
							$stories_reels = $ig->story->getReelsMediaFeed( $ids );
							} catch (\InstagramAPI\Exception\ThrottledException $e) {
                        $climate->darkGray('getReelsMediaFeedWeb Throttled! Resting during 30 minutes before try again.');
                        sleep(15*60);
						} catch ( \InstagramAPI\Exception\BadRequestException $e ) {
							// Invalid reel id list.
							// That's mean that this users don't have stories.
						} catch ( Exception $e ) {
							throw $e;
						}

						$counter1 += 1;

						if ( isset( $stories_reels ) && $stories_reels->isOk() && count( $stories_reels->getReels()->getData() ) > 0 ) {
							// Save user story reels's to array
							$reels = [];
							$reels = $stories_reels->getReels()->getData();

							foreach ( $reels as $r ) {
								$items        = [];
								$stories_loop = [];
								$items        = $r->getItems();

								foreach ( $items as $item ) {
									if ( ! $item->getId() ) {
										// Item is not valid
										continue;
									}
										$it = json_decode( $item, true );

									if ( array_key_exists( 'story_polls', $it ) ) {
										if ( ! $data->is_poll_vote_active ) {
											continue;
										}

										if ( isset( $it['story_polls'][0]['poll_sticker']['viewer_vote'] ) ) {
											continue;
										}
										try {
											$oy = mt_rand( 0, 1 );
											$resp = $ig->story->votePollStory( $it['id'], $it['story_polls'][0]['poll_sticker']['poll_id'], $oy );
											$response = json_decode($resp);
															
                                                        $now_ms = strtotime(date('Y-m-d H:i:s'));
                                            if ($now_ms - $begin_ms >= 15) {
                                                // all fine
                                            } else {
                                                $counter3 = 15 - ($now_ms - $begin_ms) + rand(1, 3);
                                                $climate->darkGray('Starting ' . $counter3 . ' second(s) delay for bypassing Instagram limits.');
                                                $vProgress = $climate->progress()->total($counter3);
                                                do {
                                                    $vProgress->advance(1, $counter3. 'second(s) left');
                                                    sleep(1);
                                                    $counter3 -= 1;
                                                } while (0 != $counter3);
                                            }

                                        $begin_ms = strtotime(date('Y-m-d H:i:s'));

                                        if ($response->status == 'ok') {
										$poll_votes_count++;
										$mycount++;
										$stories_loop[] = $item;
										$climate->magenta( date('H:i:s') .'-  Poll Voted : ' . $oy . ' Votes Given: ' . $poll_votes_count . ' Total  : ' . $mycount );
										} else {
                                                            $climate->magenta(date('H:i:s') .' - Fail to vote poll \n');
                                                               
                                                            }
											} catch (\InstagramAPI\Exception\BadRequestException $e) {
											sleep(3);
											} catch (\InstagramAPI\Exception\ThrottledException $e) {
                                                            $climate->magenta("Bypassing action limits...");
															$stories_loop[] = $item;
										} catch ( Exception $e ) {
										//throw $e;
										continue;
										}
									} elseif ( array_key_exists( 'story_sliders', $it ) ) {
										if ( ! $data->is_slider_points_active ) {
											continue;
										}

										if ( isset( $it['story_sliders'][0]['slider_sticker']['viewer_vote'] ) ) {
											continue;
										}
										try{
											$point = ( mt_rand( $data->slider_points_range[0], $data->slider_points_range[1] ) / 100 );
											$resp = $ig->story->voteSliderStory( $it['id'], $it['story_sliders'][0]['slider_sticker']['slider_id'], $point );					
 											$response = json_decode($resp);
											$now_ms = strtotime(date('Y-m-d H:i:s'));
                                            if ($now_ms - $begin_ms >= 15) {
                                                // all fine
                                            } else {
                                                $counter3 = 15 - ($now_ms - $begin_ms) + rand(1, 3);
                                                $climate->darkGray('Starting ' . $counter3 . ' second(s) delay for bypassing Instagram limits.');
                                                $vProgress = $climate->progress()->total($counter3);
                                                do {
                                                    $vProgress->advance(1, $counter3. 'second(s) left');
                                                    sleep(1);
                                                    $counter3 -= 1;
                                                } while (0 != $counter3);
                                            }
                                            $begin_ms = strtotime(date('Y-m-d H:i:s'));

                                         if ($response->status == 'ok') {
										$slider_points_count++;
										$mycount++;
										$stories_loop[] = $item;
										$climate->blue(date('H:i:s') . ' - Slider Point Give : ' . $point . ' Point : ' . $slider_points_count . ' Total Action  : ' . $mycount );
										 } else {
                                                               continue;
                                                            }
											} catch (\InstagramAPI\Exception\ThrottledException $e) {
                                                             sleep(1);
                                            $slider_throttled = true;
											$stories_loop[] = $item;

                                            $climate->blue("Bypassing action limits...");
										} catch (\InstagramAPI\Exception\BadRequestException $e) {
										} catch ( Exception $e ) {
										//throw $e;
										continue;
										}
									} elseif ( array_key_exists( 'story_questions', $it ) ) {
										if ( ! $data->is_questions_answers_active ) {
											continue;
										}
										if ( ! $it['can_reply'] ) {
											continue;
										}
										$questions    = $data->questions_answers;
										$real_respond = $questions[ mt_rand( 0, ( count( $questions ) - 1 ) ) ];
										try {
											$resp = $ig->story->answerStoryQuestion( $it['id'], $it['story_questions'][0]['question_sticker']['question_id'], $real_respond );
											$response = json_decode($resp);
                                            $now_ms = strtotime(date('Y-m-d H:i:s'));
                                            if ($now_ms - $begin_ms >= 15) {
                                                // all fine
                                            } else {
                                                $counter3 = 15 - ($now_ms - $begin_ms) + rand(1, 3);
                                                $climate->darkGray('Starting ' . $counter3 . ' second(s) delay for bypassing Instagram limits.');
                                                $vProgress = $climate->progress()->total($counter3);
                                                do {
                                                    $vProgress->advance(1, $counter3. 'second(s) left');
                                                    sleep(1);
                                                    $counter3 -= 1;
                                                } while (0 != $counter3);
                                            }
                                            $begin_ms = strtotime(date('Y-m-d H:i:s'));
                                           if ($response->status == 'ok') {
											$mycount++;
											$stories_loop[] = $item;
											$climate->yellow(date('H:i:s') . ' - Question Answered: ' . $real_respond . ' Total Actions : ' .$mycount);

										  } else {
                                                                continue;
                                                            }
										} catch (\InstagramAPI\Exception\BadRequestException $e) {
											} catch (\InstagramAPI\Exception\ThrottledException $e) {
                                            sleep(1);
                                            $climate->yellow("Bypassing action limits...");
										} catch ( \Exception $e ) {
											continue;
										}

									} elseif ( array_key_exists( 'story_quizs', $it ) ) {
										if ( ! $data->is_quiz_answers_active ) {
											continue;
										}

										if ( isset( $it['story_quizs'][0]['quiz_sticker']['viewer_answer'] ) ) {
											continue;
										}

										// Generate vote and complete quiz
										$answer_1 = isset( $it['story_quizs'][0]['quiz_sticker']['tallies'][0] ) ? 1 : 0;
										$answer_2 = isset( $it['story_quizs'][0]['quiz_sticker']['tallies'][1] ) ? 1 : 0;
										$answer_3 = isset( $it['story_quizs'][0]['quiz_sticker']['tallies'][2] ) ? 1 : 0;
										$answer_4 = isset( $it['story_quizs'][0]['quiz_sticker']['tallies'][3] ) ? 1 : 0;

										if ( $answer_1 && $answer_2 && $answer_3 && $answer_4 ) {
											$vote = mt_rand( 0, 3 );
										} elseif ( $answer_1 && $answer_2 && $answer_3 ) {
											$vote = mt_rand( 0, 2 );
										} elseif ( $answer_1 && $answer_2 ) {
											$vote = mt_rand( 0, 1 );
										}

										try {
											$resp = $ig->story->voteQuizStory( $it['pk'], $it['story_quizs'][0]['quiz_sticker']['quiz_id'], $vote );
											$response = json_decode($resp);
                                                            
                                            if ($response->status == 'ok') {
											$mycount++;
											$stories_loop[] = $item;
											$climate->lightGray( 'Quiz Answered: ' . $vote . ' Total  : ' . $mycount );
											} else {
                                                                continue;
                                                            }
										} catch (\InstagramAPI\Exception\BadRequestException $e) {
											sleep(3);
										} catch ( Exception $e ) {
											//throw $e;
											continue;
										}
									} else {
										$stories_loop[] = $item;
									}
								}

								if ( empty( $stories ) ) {
									$stories = $stories_loop;
								} else {
									$stories = array_merge( $stories, $stories_loop );
								}

								$st_count   = $st_count + count( $stories_loop );
								$view_count = $view_count + count( $stories_loop );

								$now = strtotime( date( 'Y-m-d H:i:s' ) );
								if ( $now - $begin > 299 ) {
									$begin   = strtotime( date( 'Y-m-d H:i:s' ) );
									$delitel = $delitel + 1;
									$speed   = (int) ( $view_count * 12 * 24 / $delitel );
									output_clean( '' );
									output_clean( 'Estimated speed is ' . number_format( $speed, 0, '.', ' ' ) . ' stories/day.' );
									output_clean( '© Hypervote Terminal. With 3 Hours fresh stories, you can gain more follower..' );
									output_clean( '' );
								}
								$now_f = strtotime( date( 'Y-m-d H:i:s' ) );
								if ( $now_f - $begin_f > 1 ) {
									$begin_f = strtotime( date( 'Y-m-d H:i:s' ) );
									// output($st_count . " stories found. / Debug: getReelsMediaFeed (" . $counter1 . "), markMediaSeen (" . $counter2 . ")");
									//output($st_count . " stories found.");
								}

								if ( $st_count % 1000 == 0 ) {
									// output($st_count . " stories found. / Debug: getReelsMediaFeed (" . $counter1 . "), markMediaSeen (" . $counter2 . ")");
									/// output($st_count . " stories found.");

									$now_ms = strtotime( date( 'Y-m-d H:i:s' ) );
									if ( $now_ms - $begin_ms >= 15) {
										// all fine
									} else {
										$counter3 = 15 - ( $now_ms - $begin_ms ) + rand (1, 3);
										$climate->darkGray( 'Starting ' . $counter3 . ' second(s) delay for bypassing Instagram limits.' );
										$vProgress = $climate->progress()->total($counter3);
										do {
											$vProgress =advance(1, $counter3 . ' second(s) left.' );
											sleep( 1 );
											$counter3 -= 1;
										} while ( $counter3 != 0 );
									}

									// Mark collected stories as seen
									//  $mark_seen_resp = $ig->story->markMediaSeen($stories);
									//$begin_ms = strtotime(date("Y-m-d H:i:s"));

									//      $st_count_seen = number_format($st_count, 0, '.', ' ');
									//    $counter2 += 1;
									// output($st_count_seen . " stories marked as seen. / Debug: getReelsMediaFeed (" . $counter1 . "), markMediaSeen (" . $counter2 . ").");
									//  output($st_count_seen . " stories marked as seen.");

								}
							}
						}

						// if (($st_count > 0) && $data[$key]['max_id'] == null) {
							// Mark collected stories as seen
						//    $mark_seen_resp = $ig->story->markMediaSeen($stories);

						 //   $st_count_seen = number_format($st_count, 0, '.', ' ');
						   // $counter2 += 1;
							// output($st_count_seen . " stories marked as seen. / Debug: getReelsMediaFeed (" . $counter1 . "), markMediaSeen (" . $counter2 . ").");
						   // output($st_count_seen . " stories marked voted.");
						   // output_clean("");
						 //   output_clean("Total: " . number_format($view_count, 0, '.', ' ') . " stories successfully seen.");
						 //   output_clean("");

							// Initialize arrays and parameters again
							//$stories = [];
							//$st_count = 0;
						//}

					} catch ( \InstagramAPI\Exception\NetworkException $e ) {
						$climate->error( "We couldn't connect to Instagram at the moment. Trying again." );
						sleep( 7 );
						$index_new -= 13;
						continue;
					} catch ( \InstagramAPI\Exception\EmptyResponseException $e ) {
						$climate->error( 'Instagram sent us empty response. Trying again.' );
						sleep( 7 );
						$index_new -= 13;
						continue;
					} catch ( \InstagramAPI\Exception\LoginRequiredException $e ) {
						$climate->error( 'Please login again to your Instagram account. Login required.' );
						run( $ig, $climate );
					} catch ( \InstagramAPI\Exception\ChallengeRequiredException $e ) {
						$climate->error( 'Please login again and pass verification challenge. Instagram will send you a security code to verify your identity.' );
						run( $ig, $climate );
					} catch ( \InstagramAPI\Exception\CheckpointRequiredException $e ) {
						$climate->error( 'Please go to Instagram website or mobile app and pass checkpoint!' );
						run( $ig, $climate );
					} catch ( \InstagramAPI\Exception\AccountDisabledException $e ) {
						$climate->error( 'Your account has been disabled for violating Instagram terms. Go Instagram website or mobile app to learn how you may be able to restore your account.' );
						$climate->error( 'Use this form for recovery your account: https://help.instagram.com/contact/1652567838289083' );
						run( $ig, $climate );
					} catch ( \InstagramAPI\Exception\ConsentRequiredException $e ) {
						$climate->error( 'Instagram updated Terms and Data Policy. Please go to Instagram website or mobile app to review these changes and accept them.' );
						run( $ig, $climate );
					} catch ( \InstagramAPI\Exception\SentryBlockException $e ) {
						$climate->error( 'Access to Instagram API restricted for spam behavior or otherwise abusing.' );
						run( $ig, $climate );
					} catch ( \InstagramAPI\Exception\ThrottledException $e ) {
						$climate->error( 'Throttled by Instagram because of too many API requests.' );
						$climate->error( '12 hours rest for account started because you reached Instagram daily limit for Hypervote.' );
						sleep( 43200 );
					} catch ( Exception $e ) {
						$climate->error( $e->getMessage() );
						sleep( 7 );
					}

					$index_old = $index_new + 1;

				} while ( $data_targ[ $key ]['max_id'] != null );

				// Check is $max_id is null
				if ( $data_targ[ $key ]['max_id'] == null ) {
					$climate->blue( 'All stories of @' . $d['username'] . "'s followers successfully Voted." );
					unset( $data_targ[ $key ] );
					continue;
				}

				/*if($view_count >= 14900){
					$generated_password = randomPassword();
					$change_password = $ig->account->changePassword($password,$generated_password);
					output_clean("New Password: ".$generated_password);
				}*/

			} catch ( \InstagramAPI\Exception\NetworkException $e ) {
				sleep( 7 );
			} catch ( \InstagramAPI\Exception\EmptyResponseException $e ) {
				sleep( 7 );
			} catch ( \InstagramAPI\Exception\LoginRequiredException $e ) {
				$climate->error( 'Please login again to your Instagram account. Login required.' );
				run( $ig, $climate );
			} catch ( \InstagramAPI\Exception\ChallengeRequiredException $e ) {
				$climate->error( 'Please login again and pass verification challenge. Instagram will send you a security code to verify your identity.' );
				run( $ig, $climate );
			} catch ( \InstagramAPI\Exception\CheckpointRequiredException $e ) {
				$climate->error( 'Please go to Instagram website or mobile app and pass checkpoint!' );
				run( $ig, $climate );
			} catch ( \InstagramAPI\Exception\AccountDisabledException $e ) {
				$climate->error( 'Your account has been disabled for violating Instagram terms. Go Instagram website or mobile app to learn how you may be able to restore your account.' );
				$climate->error( 'Use this form for recovery your account: https://help.instagram.com/contact/1652567838289083' );
				run( $ig, $climate );
			} catch ( \InstagramAPI\Exception\ConsentRequiredException $e ) {
				$climate->error( 'Instagram updated Terms and Data Policy. Please go to Instagram website or mobile app to review these changes and accept them.' );
				run( $ig, $climate );
			} catch ( \InstagramAPI\Exception\SentryBlockException $e ) {
				$climate->error( 'Access to Instagram API restricted for spam behavior or otherwise abusing. You can try to use Session Catcher script (available by https://nextpost.tech/session-catcher) to get valid Instagram session from location, where your account created from.' );
				run( $ig, $climate );
			} catch ( \InstagramAPI\Exception\ThrottledException $e ) {
				$climate->error( 'Throttled by Instagram because of too many API requests.' );
				$climate->error( '12 hours rest for account started because you reached Instagram daily limit for Hypervote.' );
				sleep( 43200 );
			} catch ( Exception $e ) {
				$climate->errorBold( $e->getMessage() );
				sleep( 7 );
			}
		}
	} while ( ! empty( $data_targ ) );

	$climate->blue( 'All stories related to the targets seen. Starting the new loop.' );
	$climate->blue( '' );

	hypervote_v1( $data, $targets, $ig, $delay, $fresh_stories_range, $climate );
}



/**
 * Generate Random Password
 *
 */



/**
 * Send request
 * @param $url
 * @return mixed
 */
function request( $url ) {
	$ch = curl_init();

	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

	$return = curl_exec( $ch );

	curl_close( $ch );

	return $return;
}