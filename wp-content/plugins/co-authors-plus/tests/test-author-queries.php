<?php
/**
 * Test Co-Authors Plus' modifications of author queries
 */

class Test_Author_Queries extends CoAuthorsPlus_TestCase {
	
	/**
	 * On author pages, the queried object should only be set
	 * to a user that's not a member of the blog if they
	 * have at least one published post. This matches core behavior.
	 * 
	 * @see https://core.trac.wordpress.org/changeset/27290
	 */	
	function test_author_queried_object_fix() {
		global $wp_rewrite, $coauthors_plus;

		/**
		 * Set up
		 */
		$author1 = $this->factory->user->create( array( 'user_login' => 'msauthor1' ) );
		$author2 = $this->factory->user->create( array( 'user_login' => 'msauthor2' ) );
		$blog2 = $this->factory->blog->create( array( 'user_id' => $author1 ) ); 

		switch_to_blog( $blog2 );
		$wp_rewrite->init();

		$blog2_post1 = $this->factory->post->create( array(
			'post_status'     => 'publish',
			'post_content'    => rand_str(),
			'post_title'      => rand_str(),
			'post_author'     => $author1,
			) );

		/**
		 * Author 1 is an author on the blog
		 */
		$this->go_to( get_author_posts_url( $author1 ) );
		$this->assertQueryTrue( 'is_author', 'is_archive' );

		/**
		 * Author 2 is not yet an author on the blog
		 */
		$this->go_to( get_author_posts_url( $author2 ) );
		$this->assertQueryTrue( 'is_404' );

		// Add the user to the blog
		add_user_to_blog( $blog2, $author2, 'author' );

		/**
		 * Author 2 is now on the blog, but not yet published
		 */
		$this->go_to( get_author_posts_url( $author2 ) );
		$this->assertQueryTrue( 'is_author', 'is_archive' );

		// Add the user as an author on the original post
		$author2_obj = get_user_by( 'id', $author2 );
		$coauthors_plus->add_coauthors( $blog2_post1, array( $author2_obj->user_login ), true );

		/**
		 * Author 2 is now on the blog, and published
		 */
		$this->go_to( get_author_posts_url( $author2 ) );
		$this->assertQueryTrue( 'is_author', 'is_archive' );

		// Remove the user from the blog
		remove_user_from_blog( $author2, $blog2 );

		/**
		 * Author 2 was removed from the blog, but still a published author
		 */
		$this->go_to( get_author_posts_url( $author2 ) );
		$this->assertQueryTrue( 'is_author', 'is_archive' );

		// Delete the user from the network
		wpmu_delete_user( $author2 );

		/**
		 * Author 2 is no more
		 */
		$this->go_to( get_author_posts_url( $author2 ) );
		$this->assertQueryTrue( 'is_404' );
		$this->assertEquals( false, get_user_by( 'id', $author2 ) );

		restore_current_blog();

	} 

}