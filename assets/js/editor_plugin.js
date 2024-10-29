(
	function(){
	
		var icon_url = '../images/apptivo.png';
	
		tinymce.create(
			"tinymce.plugins.ApptivoBusinesssiteShortcodes",
			{
				init: function(d,e) {},
				createControl:function(d,e)
				{
				
					if(d=="apptivo-businesssite_shortcodes_button"){
					
						d=e.createMenuButton( "apptivo-businesssite_shortcodes_button",{
							title:"Insert Apptivo Businesssite Shortcode",
							image:icon_url,
							icons:false
							});
							
							var a=this;d.onRenderMenu.add(function(c,b){
								
								
								a.addImmediate(b,"Contact Form", '[apptivocontactform name="&lt;&lt;contactform name&gt;&gt;"]');
								a.addImmediate(b,"Cases Form", '[apptivo_cases]');
																
								c=b.addMenu({title:"Testimonials"});
										a.addImmediate(c,"Full View","[apptivo_testimonials_fullview]" );
										a.addImmediate(c,"Inline View","[apptivo_testimonials_inline]" );
                                        a.addImmediate(c,"Testimonial Form",'[apptivo_testimonials_form name="testimonialform"]' );
								
								b.addSeparator();
								
								a.addImmediate(b,"Newsletter", '[apptivonewsletterform name="&lt;&lt;newsletterform name&gt;&gt;"]');
								
								c=b.addMenu({title:"Jobs"});
										a.addImmediate(c,"Job Lists","[apptivo_jobs]" );
										a.addImmediate(c,"Job Search Form",'[apptivo_job_searchform name="jsform"]');
										a.addImmediate(c,"Job Description","[apptivo_job_description]" );
										a.addImmediate(c,"Job Applicant Form",'[apptivo_job_applicantform name="jaform"]');

							});
						return d
					
					} // End IF Statement
					
					return null
				},
		
				addImmediate:function(d,e,a){d.add({title:e,onclick:function(){tinyMCE.activeEditor.execCommand( "mceInsertContent",false,a)}})}
				
			}
		);
		
		tinymce.PluginManager.add( "ApptivoBusinesssiteShortcodes", tinymce.plugins.ApptivoBusinesssiteShortcodes);
	}
)();