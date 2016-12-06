<section class="visual">
    <% if $MultipleSlides %>
        <div class="slick-slider">
            <% loop $MultipleSlides.Sort('SortOrder') %>
                <% if $Image %>
                    <div class="bg-img" style="background-image:url($Image.FocusCropHeight(850).Link());">
                        <div class="row height-100">
                            <div class="large-12 columns height-100">
                                <div class="text">
                                    <% if $Title %><h1>$Title</h1><% else %><h1>$Level(1).Title</h1><% end_if %>
                                    <% if $SubTitle %><h3>$SubTitle</h3><% end_if %>
                                    <% if $ButtonText && $LinkType != 'None' %>
                                        <% if $LinkType = 'Internal' %><a class="button" href="$Page.Link">$ButtonText</a><% end_if %>
                                        <% if $LinkType = 'External' %><a class="button" href="$LinkExternal" target="_blank">$ButtonText</a><% end_if %>
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
            <div class="bg-img<% if $Top.YoutubeLink %> video-visual" data-id="$Top.YoutubeID<% end_if %>" style="background-image:url($Image.FocusCropHeight(850).Link());">
                <div class="row height-100">
                    <div class="large-12 columns height-100">
                        <div class="text">
                            <% if $Title %><h1>$Title</h1><% else %><h1>$Level(1).Title</h1><% end_if %>
                            <% if $SubTitle %><h3>$SubTitle</h3><% end_if %>
                            <% if $ButtonText && $LinkType != 'None' %>
                                <% if $LinkType = 'Internal' %><a class="button" href="$Page.Link">$ButtonText</a><% end_if %>
                                <% if $LinkType = 'External' %><a class="button" href="$LinkExternal" target="_blank">$ButtonText</a><% end_if %>
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
</section>