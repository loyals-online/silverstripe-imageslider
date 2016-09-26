<% if $ImageSlides && $ImageSliderEnabled %>
    <div class="image-slider">
        <% loop $ImageSlides.Sort('SortOrder') %>
            <% if $Image %>
                <div>
                    <img src="$Image.FocusFill(1200,400).URL"/>
                    <% if $Title %><h2>$Title</h2><% end_if %>
                    <% if $SubTitle %><h3>$SubTitle</h3><% end_if %>
                    <% if $ButtonText && $LinkType != 'None' %>
                        <% if $LinkType = 'Internal' %><a class="button" href="$Page.Link">$ButtonText</a><% end_if %>
                        <% if $LinkType = 'External' %><a class="button" href="$LinkExternal" target="_blank">$ButtonText</a><% end_if %>
                        <% if $LinkType = 'Email' %><a class="button" href="mailto:$LinkEmail">$ButtonText</a><% end_if %>
                        <% if $LinkType = 'Telephone' %><a class="button" href="tel:$LinkTelephone">$ButtonText</a><% end_if %>
                    <% end_if %>
                </div>
            <% end_if %>
        <% end_loop %>
    </div>
<% end_if %>