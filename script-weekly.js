axios.get('docs/weekly.md')
    .then(function(response) {
        var ul = document.querySelector('ul');
        var lines = response.data.split('\n');
        lines.forEach(function(line) {
            var li = document.createElement('li');
            li.textContent = line;
            ul.appendChild(li);
        });
    })
    .catch(function(error) {
        console.error('Error fetching daily.md:', error);
    });
