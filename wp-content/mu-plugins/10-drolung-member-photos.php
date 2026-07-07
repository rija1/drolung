<?php
/**
 * Importe les photos du bureau et seed les données membres pour DSF et DSM.
 *
 * Déclenchement unique par site via gate `drolung_member_photos_v1` (option per-blog).
 * Pour rejouer : supprimer l'option sur le site concerné.
 *   WP admin → Outils → supprimez 'drolung_member_photos_v1' dans wp_options
 *   ou : wp option delete drolung_member_photos_v1 --url=dsf.drolung.local
 *
 * Sources photos :
 *   DSF : mockup-dsf/bureau/ (Barbara.JPEG, Petra.JPEG, Rija.jpg)
 *   DSM : mockup-dsm/bureau/ (Francine.jpg, Hajasoa.jpg, Hajatiana.jpg, Rija.jpg)
 *   Suzy Ratsimbazafy (DSM membre 5) n'a pas de photo disponible — initiales affichées.
 *
 * @package drolung-network
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'admin_init', 'drolung_seed_member_photos' );

function drolung_seed_member_photos() {
	$blog_id = get_current_blog_id();

	if ( ! in_array( $blog_id, [ 3, 4 ], true ) ) { return; }
	if ( get_option( 'drolung_member_photos_v1' ) ) { return; }

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$page = get_page_by_path( 'a-propos' );
	if ( ! $page ) { return; }

	if ( 4 === $blog_id ) {
		drolung_seed_bureau_dsf( $page->ID );
	} else {
		drolung_seed_bureau_dsm( $page->ID );
	}

	update_option( 'drolung_member_photos_v1', current_time( 'mysql' ) );
}

/**
 * Copie un fichier local dans wp-content/uploads et l'enregistre comme attachment.
 *
 * @param string $src_path  Chemin absolu vers l'image source.
 * @param int    $post_id   ID du post parent (page à-propos).
 * @param string $title     Titre de l'attachment WP.
 * @return int|null  Attachment ID, ou null en cas d'échec.
 */
function drolung_import_local_photo( $src_path, $post_id, $title ) {
	if ( ! file_exists( $src_path ) ) { return null; }

	$upload = wp_upload_dir();
	if ( ! empty( $upload['error'] ) ) { return null; }

	$filename  = wp_unique_filename( $upload['path'], basename( $src_path ) );
	$dest_path = $upload['path'] . '/' . $filename;

	if ( ! @copy( $src_path, $dest_path ) ) { return null; }

	$filetype  = wp_check_filetype( $dest_path );
	$attach_id = wp_insert_attachment(
		[
			'guid'           => $upload['url'] . '/' . $filename,
			'post_mime_type' => $filetype['type'],
			'post_title'     => $title,
			'post_content'   => '',
			'post_status'    => 'inherit',
		],
		$dest_path,
		$post_id
	);

	if ( is_wp_error( $attach_id ) ) { return null; }

	wp_update_attachment_metadata( $attach_id, wp_generate_attachment_metadata( $attach_id, $dest_path ) );

	return $attach_id;
}

function drolung_seed_bureau_dsf( $page_id ) {
	$dir = '/Users/reedz/Desktop/Desktop/Sekhar/Website/Drolung/Mock/mockup-dsf/bureau/';

	$members = [
		1 => [ 'name' => 'Petra Hoelscher',   'file' => $dir . 'Petra.JPEG'    ],
		2 => [ 'name' => 'Rija Ratinahirana',  'file' => $dir . 'Rija.jpg'      ],
		3 => [ 'name' => 'Barbara Stuetz',     'file' => $dir . 'Barbara.JPEG'  ],
	];

	foreach ( $members as $i => $m ) {
		$id = drolung_import_local_photo( $m['file'], $page_id, $m['name'] );
		if ( $id ) {
			// update_field() stores the hidden _meta key so ACF knows the type (image → URL).
			update_field( "field_apropos_member_{$i}_photo", $id, $page_id );
		}
	}
}

function drolung_seed_bureau_dsm( $page_id ) {
	$dir = '/Users/reedz/Desktop/Desktop/Sekhar/Website/Drolung/Mock/mockup-dsm/bureau/';

	$members = [
		1 => [
			'role' => 'Président',
			'name' => 'Rija Ratinahirana',
			'bio'  => 'Franco-malgache, titulaire d\'un master en informatique. Sa pratique du bouddhisme tibétain et ses origines malgaches ont forgé sa conviction qu\'un développement juste vient de l\'intérieur des communautés. Il assure le lien stratégique et opérationnel entre Madagascar et la France.',
			'file' => $dir . 'Rija.jpg',
		],
		2 => [
			'role' => 'Vice-Présidente',
			'name' => 'Hajasoa Ravololonirina',
			'bio'  => 'Docteure en Sciences du Langage et agrégée de lettres, chercheuse associée au LCF de l\'Université de La Réunion. Son parcours entre Madagascar et La Réunion nourrit sa réflexion sur la diversité linguistique et la double appartenance culturelle.',
			'file' => $dir . 'Hajasoa.jpg',
		],
		3 => [
			'role' => 'Trésorière',
			'name' => 'Francine Ratsimbazafy',
			'bio'  => 'Biographie à venir.',
			'file' => $dir . 'Francine.jpg',
		],
		4 => [
			'role' => 'Secrétaire',
			'name' => 'Hajatiana Randriamialisoa',
			'bio'  => 'Biographie à venir.',
			'file' => $dir . 'Hajatiana.jpg',
		],
		5 => [
			'role' => '',
			'name' => 'Suzy Ratsimbazafy',
			'bio'  => 'Biographie à venir.',
			'file' => '',
		],
	];

	update_field( 'field_apropos_bureau_intro', 'Cinq membres bénévoles, ancrés dans la réalité du terrain franco-malgache et engagés dans une gouvernance collégiale.', $page_id );

	foreach ( $members as $i => $m ) {
		update_field( "field_apropos_member_{$i}_role", $m['role'], $page_id );
		update_field( "field_apropos_member_{$i}_name", $m['name'], $page_id );
		update_field( "field_apropos_member_{$i}_bio",  $m['bio'],  $page_id );

		if ( $m['file'] ) {
			$id = drolung_import_local_photo( $m['file'], $page_id, $m['name'] );
			if ( $id ) {
				update_field( "field_apropos_member_{$i}_photo", $id, $page_id );
			}
		}
	}
}
