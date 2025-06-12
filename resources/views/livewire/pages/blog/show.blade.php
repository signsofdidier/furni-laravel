<div>
    <div class="article-page mt-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-12 col-12">
                    <div class="article-rte">
                        <div class="article-img">
                            <img src="{{ url('storage', $blog->image) }}" alt="img">
                        </div>
                        <div class="article-meta">
                            <h2 class="article-title">{{  $blog->title }}</h2>
                            <div class="article-card-published text_14 d-flex align-items-center">
                                        <span class="article-author d-flex align-items-center">
                                            <span class="icon-author"><svg width="15" height="17"
                                                                           viewBox="0 0 15 17" fill="none"
                                                                           xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M7.5 0.59375C4.72888 0.59375 2.46875 2.85388 2.46875 5.625C2.46875 7.3573 3.35315 8.89587 4.69238 9.80274C2.12903 10.9033 0.3125 13.447 0.3125 16.4063H1.75C1.75 13.2224 4.31616 10.6563 7.5 10.6563C10.6838 10.6563 13.25 13.2224 13.25 16.4063H14.6875C14.6875 13.447 12.871 10.9033 10.3076 9.80274C11.6469 8.89587 12.5313 7.3573 12.5313 5.625C12.5313 2.85388 10.2711 0.59375 7.5 0.59375ZM7.5 2.03125C9.49341 2.03125 11.0938 3.63159 11.0938 5.625C11.0938 7.61841 9.49341 9.21875 7.5 9.21875C5.50659 9.21875 3.90625 7.61841 3.90625 5.625C3.90625 3.63159 5.50659 2.03125 7.5 2.03125Z"
                                                        fill="#00234D" />
                                                </svg>
                                            </span>
                                            <span class="ms-2">{{ $blog->user->name }}</span>
                                        </span>

                                <span class="article-separator mx-3 d-none d-sm-block">
                                            <svg width="2" height="12" viewBox="0 0 2 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.4" d="M1.09761 0.5H0V11.5H1.09761V0.5Z" fill="black"/>
                                            </svg>
                                        </span>

                                <div class="article-date d-none d-sm-flex align-items-center">
                                            <span class="icon-tag">
                                                <svg width="17" height="18" viewBox="0 0 17 18" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M3.46875 0.875V1.59375H0.59375V17.4063H16.4063V1.59375H13.5313V0.875H12.0938V1.59375H4.90625V0.875H3.46875ZM2.03125 3.03125H3.46875V3.75H4.90625V3.03125H12.0938V3.75H13.5313V3.03125H14.9688V4.46875H2.03125V3.03125ZM2.03125 5.90625H14.9688V15.9688H2.03125V5.90625ZM6.34375 7.34375V8.78125H7.78125V7.34375H6.34375ZM9.21875 7.34375V8.78125H10.6563V7.34375H9.21875ZM12.0938 7.34375V8.78125H13.5313V7.34375H12.0938ZM3.46875 10.2188V11.6563H4.90625V10.2188H3.46875ZM6.34375 10.2188V11.6563H7.78125V10.2188H6.34375ZM9.21875 10.2188V11.6563H10.6563V10.2188H9.21875ZM12.0938 10.2188V11.6563H13.5313V10.2188H12.0938ZM3.46875 13.0938V14.5313H4.90625V13.0938H3.46875ZM6.34375 13.0938V14.5313H7.78125V13.0938H6.34375ZM9.21875 13.0938V14.5313H10.6563V13.0938H9.21875Z"
                                                        fill="#00234D" />
                                                </svg>
                                            </span>
                                    <span class="ms-2">{{ $blog->created_at->translatedFormat('d F Y') }}</span>
                                </div>

                                <span class="article-separator mx-3 d-none d-sm-block">
                                            <svg width="2" height="12" viewBox="0 0 2 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.4" d="M1.09761 0.5H0V11.5H1.09761V0.5Z" fill="black"/>
                                            </svg>
                                        </span>

                                <div class="article-date d-none d-sm-flex align-items-center">
                                            <span class="icon-tag">
                                                <svg width="22" height="18" viewBox="0 0 22 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M7.09375 0.65625L6.8125 1.10156C6.8125 1.10156 5.86621 2.55762 4.65625 4.21875C3.44629 5.87988 1.91992 7.76074 1.02344 8.39062L0.648438 8.64844L0.695312 9.09375L1.44531 15.8438L1.51562 16.5H2.19531C3.30859 16.5 4.60645 16.5586 5.89844 16.6406L6.69531 16.6875V10.5H8.19531V16.8047L8.875 16.875C10.9961 17.0625 12.6016 17.25 12.6016 17.25H12.7188L20.9219 15.6094L20.9453 15.0234C20.9453 15.0234 21.0127 13.7725 21.1328 12.3047C21.2529 10.8369 21.4492 9.11133 21.6484 8.48438L21.8125 7.96875L21.4141 7.66406C18.8271 5.66016 16.3516 1.14844 16.3516 1.14844L16.1172 0.679688L15.5781 0.75C15.5781 0.75 10.7588 1.41504 7.58594 0.773438L7.09375 0.65625ZM15.2969 2.29688C15.6045 2.86816 17.1221 5.58105 19.3047 7.73438L13.6562 8.625C11.8076 6.84961 9.92383 4.04297 8.89844 2.41406C11.8018 2.71289 14.667 2.38184 15.2969 2.29688ZM7.39844 2.90625C8.38281 4.49121 10.4482 7.60254 12.625 9.67969C12.1328 11.6865 11.9834 14.3613 11.9453 15.6797C11.4443 15.627 10.8408 15.5537 9.69531 15.4453V9H5.19531V15.1172C4.39551 15.0732 3.61914 15.0352 2.875 15.0234L2.24219 9.25781C3.45215 8.27344 4.74707 6.65625 5.875 5.10938C6.60742 4.10156 7.06445 3.41309 7.39844 2.90625ZM20.0312 9.16406C19.8555 10.0576 19.7207 11.1123 19.6328 12.1641C19.5361 13.3506 19.5127 14.001 19.4922 14.3906L13.4688 15.6094C13.5068 14.2881 13.6445 11.7891 14.0312 10.1016L20.0312 9.16406Z" fill="black"/>
                                                </svg>
                                            </span>

                                    {{--BLOG CATEGORIES --}}
                                    <span class="ms-2">
                                        {{ $blog->categories->pluck('name')->join(', ') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="article-content [&>ul]:list-disc [&>ul]">
                            {{--install composer require league/commonmark om markdown te doen werken--}}
                            {!!
                                str($blog->content)
                                    ->markdown([
                                        // Strip alle raw HTML-tags uit de bron
                                        'html_input' => 'strip',
                                        // Blokkeer links die beginnen met “javascript:” of andere onveilige protocollen
                                        'allow_unsafe_links' => false,
                                    ])
                                    // Verwijder alles wat niet in de standaard allowlist valt (XSS-preventie)
                                    ->sanitizeHtml()
                            !!}

                            <figure class="blockquote">
                                <blockquote>
                                    {{ $blog->blockquote }}
                                </blockquote>
                                <figcaption>{{ $blog->blockquote_author }}</figcaption>
                            </figure>

                            {{--<h6 class="heading_24 mb-3 mt-5">A Perfect product for you</h6>
                            <p>It is a long established fact that a reader will be distracted by the readable content of a page looking at its layout.The point of using Lorem Ipsum is that it has a more-or-less normal distribu letters, as opposed to using ‘Content here, content here’, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their.</p>--}}
                        </div>

                        <div class="next-prev-article mt-5 d-flex align-items-center justify-content-between">
                            <a href="article.html" class="article-btn prev-article-btn mt-2">PREV ARTICLE</a>
                            <a href="article.html" class="article-btn next-article-btn active mt-2">NEXT ARTICLE</a>
                        </div>

                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-12">
                    <div class="filter-drawer blog-sidebar">
                        <div class="filter-widget">
                            <div class="filter-header faq-heading heading_18 d-flex align-items-center border-bottom">
                                Latest Post
                            </div>
                            <div class="filter-related">
                                <div class="related-item related-item-article d-flex">
                                    <div class="related-img-wrapper">
                                        <img class="related-img" src="{{ asset('assets/img/blog/furniture-1.jpg') }}" alt="img">
                                    </div>
                                    <div class="related-product-info">
                                        <h2 class="related-heading text_14">
                                            <a href="article.html">Modern furniture in velvet</a>
                                        </h2>
                                        <p class="article-card-published text_12 d-flex align-items-center mt-2">
                                                    <span class="article-date d-flex align-items-center">
                                                        <span class="icon-publish">
                                                            <svg width="17" height="18" viewBox="0 0 17 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M3.46875 0.875V1.59375H0.59375V17.4063H16.4063V1.59375H13.5313V0.875H12.0938V1.59375H4.90625V0.875H3.46875ZM2.03125 3.03125H3.46875V3.75H4.90625V3.03125H12.0938V3.75H13.5313V3.03125H14.9688V4.46875H2.03125V3.03125ZM2.03125 5.90625H14.9688V15.9688H2.03125V5.90625ZM6.34375 7.34375V8.78125H7.78125V7.34375H6.34375ZM9.21875 7.34375V8.78125H10.6563V7.34375H9.21875ZM12.0938 7.34375V8.78125H13.5313V7.34375H12.0938ZM3.46875 10.2188V11.6563H4.90625V10.2188H3.46875ZM6.34375 10.2188V11.6563H7.78125V10.2188H6.34375ZM9.21875 10.2188V11.6563H10.6563V10.2188H9.21875ZM12.0938 10.2188V11.6563H13.5313V10.2188H12.0938ZM3.46875 13.0938V14.5313H4.90625V13.0938H3.46875ZM6.34375 13.0938V14.5313H7.78125V13.0938H6.34375ZM9.21875 13.0938V14.5313H10.6563V13.0938H9.21875Z" fill="#00234D"></path>
                                                            </svg>
                                                        </span>
                                                        <span class="ms-2">30 December, 2022</span>
                                                    </span>
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="filter-widget">
                            <a href="collection-left-sidebar.html">
                                <img class="rounded" src="{{ asset('assets/img/products/furniture/1.jpg') }}" alt="img">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .markdown-content h1, h2, h3 {
            margin-top: 1.5rem;
        }

        .markdown-content p {
            margin-bottom: 1rem;
        }

    </style>
</div>
