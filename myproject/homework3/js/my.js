function cityCount (num) {
      var count = document.getElementById('city-count');
      count.innerHTML = num;
    }


    function showCities (country) {
      var cityList = document.getElementById('city-list');
      cityList.innerHTML = '';
      var fragment = document.createDocumentFragment();
      for (var i = 0, len = countries[country].length; i < len; ++i) {
        var item = document.createElement("li");
        item.className = 'list-group-item';
        item.innerHTML = countries[country][i];
        fragment.appendChild(item);
      }
      cityList.appendChild(fragment);
      cityCount(len);
    }


    function showAllCountries (countries) {
      var countriesList = document.getElementById('country');
      var fragment = document.createDocumentFragment();
      for (var i = 0, len = countries.length; i < len; ++i) {
        var item = document.createElement("li");
        item.innerHTML = '<a role="menuitem" tabindex="-1" href="#" class="country-link">' + countries[i] + '</a>';
        fragment.appendChild(item);
      }
      countriesList.appendChild(fragment);
    }


    function choseCountry () {
      var select = document.getElementsByClassName('country-link');
      var showSelectCountry = document.getElementById('u-chose');
      for (var i = 0, len = select.length; i < len; ++i) {
          select[i].onclick = function () {
            showCities(this.innerHTML);
            work.showCities(this.innerHTML);
            showSelectCountry.innerHTML = this.innerHTML;
          }
      } 
    }
    work.show();
    choseCountry();