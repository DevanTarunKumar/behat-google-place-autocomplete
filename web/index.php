<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Behat Google Places Autocompete</title>
    <link rel="stylesheet" href="bower_components/foundation-sites/dist/css/foundation.min.css">
</head>
<body>

    <div class="row">
        <div class="smalll-12 columns">
            <h1>Behat Google Place Autocomplete</h1>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
            <label for="contact_street_address">Your Street Address:</label>
            <input type="text" id="contact_street_address" placeholder="Enter Address" name="contact[address]" required><div id="loading_address_form"></div>
            <small class="error">The Address is Required</small>
        </div>
    </div>

    <div class="row">
        <div class="medium-4 columns">
            <label for="locality">City:</label>
            <input type="text" id="locality" placeholder="Enter City" name="contact[city]" required>
        </div>
        <div class="medium-4 columns">
            <label for="administrative_area_level_1">State:</label>
            <select id="administrative_area_level_1" name="contact[state]" required>
                <option value="" disabled selected>State</option>
                <option value="AL">Alabama</option>
                <option value="AK">Alaska</option>
                <option value="AZ">Arizona</option>
                <option value="AR">Arkansas</option>
                <option value="CA">California</option>
                <option value="CO">Colorado</option>
                <option value="CT">Connecticut</option>
                <option value="DE">Delaware</option>
                <option value="DC">District of Columbia</option>
                <option value="FL">Florida</option>
                <option value="GA">Georgia</option>
                <option value="HI">Hawaii</option>
                <option value="ID">Idaho</option>
                <option value="IL">Illinois</option>
                <option value="IN">Indiana</option>
                <option value="IA">Iowa</option>
                <option value="KS">Kansas</option>
                <option value="KY">Kentucky</option>
                <option value="LA">Louisiana</option>
                <option value="ME">Maine</option>
                <option value="MD">Maryland</option>
                <option value="MA">Massachusetts</option>
                <option value="MI">Michigan</option>
                <option value="MN">Minnesota</option>
                <option value="MS">Mississippi</option>
                <option value="MO">Missouri</option>
                <option value="MT">Montana</option>
                <option value="NE">Nebraska</option>
                <option value="NV">Nevada</option>
                <option value="NH">New Hampshire</option>
                <option value="NJ">New Jersey</option>
                <option value="NM">New Mexico</option>
                <option value="NY">New York</option>
                <option value="NC">North Carolina</option>
                <option value="ND">North Dakota</option>
                <option value="OH">Ohio</option>
                <option value="OK">Oklahoma</option>
                <option value="OR">Oregon</option>
                <option value="PA">Pennsylvania</option>
                <option value="PR">Puerto Rico</option>
                <option value="RI">Rhode Island</option>
                <option value="SC">South Carolina</option>
                <option value="SD">South Dakota</option>
                <option value="TN">Tennessee</option>
                <option value="TX">Texas</option>
                <option value="UT">Utah</option>
                <option value="VT">Vermont</option>
                <option value="VA">Virginia</option>
                <option value="WA">Washington</option>
                <option value="WV">West Virginia</option>
                <option value="WI">Wisconsin</option>
                <option value="WY">Wyoming</option></select>
        </div>
        <div class="medium-4 columns">
            <label for="postal_code">Zip Code:</label>
            <input type="tel" id="postal_code" name="contact[zip]" placeholder="Enter Zip Code" required />
            <input type="text" id="country" name="contact[country]" value="" style="display: none;" />
        </div>
    </div>
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script>
        var AutoComplete = function () {
            var self = {};
            /**
             * This example displays an address form, using the autocomplete feature
             * of the Google Places API to help users fill in the information.
             *
             * @var autocomplete Sets the Google maps places autocomplete
             * @var compnentForm Configuraiton for the form of the autocomplete
             */
            var autocomplete;
            var componentForm = {
                locality: 'long_name',
                administrative_area_level_1: 'short_name',
                country: 'long_name',
                postal_code: 'short_name'
            };

            self.init = function() {
                // Create the autocomplete object, restricting the search
                // to geographical location types.
                autocomplete = new google.maps.places.Autocomplete(
                    /** @type {HTMLInputElement} */(document.getElementById('contact_street_address')),
                    { types: ['geocode'] });
                // When the user selects an address from the dropdown,
                // populate the address fields in the form.
                google.maps.event.addListener(autocomplete, 'place_changed', fillInAddressImpl);
            };


            function fillInAddressImpl(){
                var $loading = $("#loading_address_form");
                $loading.show();
                // Get the place details from the autocomplete object.
                var place = autocomplete.getPlace();

                for (var component in componentForm) {
                    document.getElementById(component).value = '';
                    document.getElementById(component).disabled = false;
                }

                // Get each component of the address from the place details
                // and fill the corresponding field on the form.
                var prOverride = false;
                var placeAddressComponentsLength = (place.address_components || []).length;
                for (var i=0; i < placeAddressComponentsLength; i++) {
                    if (place.address_components[i].types[0] == 'country' && place.address_components[i]['long_name'] == 'Puerto Rico') {
                        prOverride = true;
                    }
                }
                for (var i = 0; i < (place.address_components || []).length; i++) {
                    var addressType = place.address_components[i].types[0];
                    if (componentForm[addressType]) {
                        var val = place.address_components[i][componentForm[addressType]];
                        if (addressType == 'administrative_area_level_1' && prOverride) {
                            val = 'PR';
                        }
                        document.getElementById(addressType).value = val;
                    }
                }
                var addressPatient = document.getElementById("contact_street_address").value;
                document.getElementById('contact_street_address').value = addressPatient.substr(0, addressPatient.indexOf(','));
                $loading.hide();
            }

            return self;
        }();

        // CODE BELOW THIS LINE IS THE AUTOCOMPLETE MOCK.

        // Test autocomplete mock. Only handles completing the specific address "5821 Southwest Freeway".
        var testElement =
            '<div class="pac-container pac-logo" style="width: 1438px;left: 0px;top: 139px;">' +
                '<div class="pac-item">' +
                    '<span class="pac-icon pac-icon-marker"></span>' +
                    '<span class="pac-item-query">' +
                        '<span class="pac-matched">5821</span>' +
                        '<span class="pac-matched">Southwest Freeway</span>' +
                    '</span>' +
                    '<span>Houston, TX, USA</span>' +
                '</div>' +
                '<div class="pac-item">' +
                    '<span class="pac-icon pac-icon-marker"></span>' +
                    '<span class="pac-item-query">' +
                        '<span class="pac-matched">5821 Southwest Freeway</span>' +
                        ' Service Road' +
                    '</span>' +
                    '<span>Houston, TX, USA</span>' +
                '</div>' +
            '</div>';

        google = {maps: {
            places: {Autocomplete: AutocompleteMock},
            event: {addListener: AddMockListener}
        }};

        /**
         * Initializes the mocked Autocomplete API.
         *
         * @param {HTMLInputElement} element The input that Autocomplete should be bound to.
         * @constructor
         */
        function AutocompleteMock(element) {
            this.element = element;
            var autocomplete = this;

            $(element).on('change', function() {
                if ($(element).val().indexOf('5821 Southwest') === 0 && !autocomplete.open) {
                    autocomplete.open = true;

                    var newElement = $(testElement);
                    $('body').append(newElement);

                    newElement.on('click', '.pac-item', function() {
                        newElement.remove();
                        autocomplete.open = false;
                        $(element).val('5821 Southwest Freeway, Houston, TX');
                        autocomplete.selectCallback();
                    });
                }
            })
        }

        AutocompleteMock.prototype = {};

        /**
         * Mocks the addListener binding for the Autocomplete mock. Only handles selecting an address suggestion.
         *
         * @param {AutocompleteMock} autocomplete The Autocomplete mock to add a listener for.
         * @param {string}           event        The event to listen for. Only supports 'place_changed'.
         * @param {function()}       callback     The callback to execute when selecting an address suggestion.
         */
        function AddMockListener(autocomplete, event, callback) {
            if (event !== 'place_changed') {
                throw 'This mock only implements the "place_changed" event';
            }

            autocomplete.selectCallback = callback;
        }

        /**
         * Returns a mock address approximately matching Google's Places syntax.
         *
         * @returns {{address_components: *[]}}
         */
        AutocompleteMock.prototype.getPlace = function() {
            return {
                address_components: [
                    {
                        types: ['locality'],
                        long_name: 'Houston',
                    },
                    {
                        types: ['administrative_area_level_1'],
                        short_name: 'TX',
                    },
                    {
                        types: ['country'],
                        long_name: 'United States',
                    },
                    {
                        types: ['postal_code'],
                        short_name: '77057',
                    }
                ]
            };
        };

        // Initialize custom autocomplete code. Normally the Google API would be passed this as the callback.
        AutoComplete.init();
    </script>
</body>
</html>
