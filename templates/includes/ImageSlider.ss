<section class="visual">
    <% if $hasImageSlider %>
        <% if $MultipleSlides %>
            <div class="slick-slider">
                <% loop $MultipleSlides.Sort('SortOrder') %>
                    <% if $Image %>
                        <div class="bg-img" style="background-image:url('$Image.FocusFill(1600,480).Link()'); background-position: $Image.PercentageX% $Image.PercentageY%;">
                            <div class="row height-100">
                                <div class="large-12 columns height-100">
                                    <div class="text">
                                        <% if $Title %><h1>$Title</h1><% else %><h1>$Level(1).Title</h1><% end_if %>
                                        <% if $SubTitle %><h3>$SubTitle</h3><% end_if %>
                                        <% if $ButtonText && $LinkType != 'None' %>
                                            <% if $LinkType = 'Internal' %><a class="button" <% if $OpenInNewWindow %>target="_blank"<% end_if %> href="$Page.Link" >$ButtonText</a><% end_if %>
                                            <% if $LinkType = 'BlogPost' && $BlogPostID %><a class="button" <% if $OpenInNewWindow %>target="_blank"<% end_if %> href="$BlogPost.Link" >$ButtonText</a><% end_if %>
                                            <% if $LinkType = 'External' %><a class="button" <% if $OpenInNewWindow %>target="_blank"<% end_if %> href="$LinkExternal" target="_blank">$ButtonText</a><% end_if %>
                                            <% if $LinkType = 'Email' %><a class="button" href="mailto:$LinkEmail">$ButtonText</a><% end_if %>
                                            <% if $LinkType = 'Telephone' %><a class="button" href="tel:$LinkTelephone">$ButtonText</a><% end_if %>
                                        <% end_if %>

                                    </div>
                                </div>
                            </div>
                        </div>
                    <% end_if %>
                <% end_loop %>
            </div>
        <% else_if $SingleImage %>
            <% with $SingleImage %>
                <div class="bg-img<% if $Top.YoutubeLink %> video-visual" data-id="$Top.YoutubeID<% end_if %>" style="background-image:url('$FocusFill(1600,480).Link()'); background-position: $Image.PercentageX% $Image.PercentageY%;">
                    <div class="row height-100">
                        <div class="large-12 columns height-100">
                            <div class="text">
                                <% if $Title %><h1>$Title</h1><% else %><h1>$Level(1).Title</h1><% end_if %>
                                <% if $SubTitle %><h3>$SubTitle</h3><% end_if %>
                                <% if $ButtonText && $LinkType != 'None' %>
                                    <% if $LinkType = 'Internal' %><a class="button" <% if $OpenInNewWindow %>target="_blank"<% end_if %> href="$Page.Link">$ButtonText</a><% end_if %>
                                    <% if $LinkType = 'BlogPost' && $BlogPostID %><a class="button" <% if $OpenInNewWindow %>target="_blank"<% end_if %> href="$BlogPost.Link">$ButtonText</a><% end_if %>
                                    <% if $LinkType = 'External' %><a class="button" <% if $OpenInNewWindow %>target="_blank"<% end_if %> href="$LinkExternal" target="_blank">$ButtonText</a><% end_if %>
                                    <% if $LinkType = 'Email' %><a class="button" href="mailto:$LinkEmail">$ButtonText</a><% end_if %>
                                    <% if $LinkType = 'Telephone' %><a class="button" href="tel:$LinkTelephone">$ButtonText</a><% end_if %>
                                <% end_if %>
                            </div>
                        </div>
                    </div>
                </div>
            <% end_with %>
        <% else %>
            <!-- fallback -->
        <% end_if %>
    <% else_if $hasSingleImage && $SingleHeaderImage %>
        <div class="bg-img" style="background-image:url('$SingleHeaderImage.FocusFill(1600,480).Link()'); background-position: $SingleHeaderImage.PercentageX% $SingleHeaderImage.PercentageY%;"></div>
    <% end_if %>
</section>
