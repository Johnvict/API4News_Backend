var URL = 'http://localhost:8000/';
// var URL = 'http://news.adeunique.com/';
var lazyload = document.createElement('script');
lazyload.src = URL + "newsClient/lazyload-image.js";

var translator = document.createElement('script');
translator.src = URL + "newsClient/translate.js";

var linK = document.createElement('link');
linK.href = "https://fonts.googleapis.com/css?family=Roboto";
linK.rel = "stylesheet";
var heaD = document.getElementsByTagName('head');
document.head.appendChild(linK);

var googleTrnDiv = document.createElement('div');
googleTrnDiv.id = "google_translate_element";

var googleTrnScrp = document.createElement('script');
googleTrnScrp.type = "text/javascript";
googleTrnScrp.src = "http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit";

var googleTrnScrp2 = document.createElement('script');
googleTrnScrp2.type = "text/javascript";
var googl = function googleTranslateElementInit() {
    new google.translate.TranslateElement({ pageLanguage: 'en' }, 'google_translate_element');
};
googleTrnScrp2.innerHTML = googl;

document.body.appendChild(googleTrnDiv);
document.body.appendChild(googleTrnScrp);
document.body.appendChild(googleTrnScrp2);


document.body.appendChild(lazyload);

var domain = window.location.host;
console.log(domain);

function getNewsBlock() {
    const block = document.getElementById('api4news');
    console.log({block: block});

    if (block == null) {
        setTimeout(() => {
            getNewsBlock();
        }, 3000);
    } else {
        return block;
    }
}

var newsContainer = getNewsBlock();
// var newsContainer = document.getElementById('api4news');

$.ajax({
    method: 'POST',
    url: URL + 'ppe/getNews',
    data: { requestUrl: domain }
})
    .done(function (response) {
        console.log("Response ", response);

        if (response.success) {
            var counter = 0;
            response.news.forEach(category => {
                console.log({ newsTitle: category.category });
                category.articles.forEach(newsData => {
                    // console.log(article)
                    // });
                    // });

                    // Array.prototype.forEach.call(response.news, function (newsData) {
                    news_block = document.createElement('div');
                    news_block.id = counter++;
                    news_block.className = "card-small box"

                    var newsImage = document.createElement('img');
                    newsImage.className = "image lazyload";
                    // newsImage.src =  newsData.urlToImage;

                    // newsImage.setAttribute('data-src', newsData.urlToImage);
                    newsImage.setAttribute('data-src', 'http://localhost:8000/file/nature.jpg');
                    news_block.appendChild(newsImage);

                    var content = document.createElement('div');
                    content.className = 'newsBody';

                    var newsTitle = document.createElement('h3');
                    newsTitle.innerHTML = newsData.title;
                    content.appendChild(newsTitle);

                    var newsAuthor = document.createElement('h4');
                    newsAuthor.innerHTML = `By:  ${newsData.author}`;
                    newsAuthor.className = "authorName"
                    if (newsData.author != "null") {
                        content.appendChild(newsAuthor);
                    }

                    var hr = document.createElement('hr');
                    content.appendChild(hr);

                    var newsBody = document.createElement('div');
                    newsBody.className = "padding";

                    var btnDiv = document.createElement('div');
                    btnDiv.className = "btnDiv";
                    var readFullNews = document.createElement('a');
                    readFullNews.href = newsData.url;
                    readFullNews.className = "button";
                    readFullNews.id = `btn_${counter}`
                    readFullNews.target = "_blank";
                    readFullNews.innerHTML = "Read more";
                    btnDiv.appendChild(readFullNews);

                    var newsContent = document.createElement('p');
                    newsContent.innerHTML = newsData != "null" ? `${newsData.content.substr(0, 250)}...` : "";
                    newsBody.appendChild(newsContent);
                    newsBody.appendChild(btnDiv);
                    // newsBody.appendChild(readMoreButton);
                    content.appendChild(newsBody);


                    news_block.appendChild(content);
                    var details = document.createElement('div');
                    details.className = "details";

                    // var newsDescription;
                    var date = new Date(newsData.publishedAt);

                    var newsDate = document.createElement('i');
                    newsDate.className = "newsDate";
                    newsDate.innerHTML = `Published on:   ${date.getUTCFullYear().toString()}/
                    ${(date.getUTCMonth() + 1).toString()}/${date.getUTCDate()}`;

                    details.appendChild(newsDate);
                    content.appendChild(details);
                    newsContainer.appendChild(news_block);
                });

            });

            if (response.old !== false) {
                // return;
                var oldNews = document.createElement('h1');
                oldNews.innerHTML = "Older News";
                newsContainer.appendChild(oldNews);

                response.old.forEach(category => {
                    console.log({ newsTitle: category.category });
                    category.articles.forEach(newsData => {
                // Array.prototype.forEach.call(response.old, function (newsData) {

                    news_block = document.createElement('div');
                    news_block.id = counter++;
                    news_block.className = "card-small trn"

                    var newsImage = document.createElement('img');
                    newsImage.className = "image lazyload";
                    // newsImage.src =  newsData.urlToImage;
                    newsImage.setAttribute('data-src', newsData.urlToImage);
                    news_block.appendChild(newsImage);

                    var content = document.createElement('div');
                    content.className = 'newsBody';

                    var newsTitle = document.createElement('h3');
                    newsTitle.innerHTML = newsData.type + ' -  ' + newsData.title;
                    content.appendChild(newsTitle);

                    var newsAuthor = document.createElement('h6');
                    newsAuthor.innerHTML = "By " + newsData.author;
                    if (newsData.author === "null") {
                        console.log('Author null');
                    } else {
                        console.log('Author Not null');
                        content.appendChild(newsAuthor);
                    }

                    var newsBody = document.createElement('div');
                    newsBody.className = "padding";

                    var readFullNews = document.createElement('a');
                    readFullNews.href = newsData.url;
                    // readFullNews.href = "#";
                    readFullNews.className = "button";
                    readFullNews.id = "btn_" + counter
                    readFullNews.target = "_blank";
                    readFullNews.innerHTML = "Read more";

                    var newsContent = document.createElement('p');
                    newsContent.innerHTML = newsData != "null" ? newsData.content.substr(0, 250) + "..." : "";
                    newsBody.appendChild(newsContent);
                    newsBody.appendChild(readFullNews);
                    // newsBody.appendChild(readMoreButton);
                    content.appendChild(newsBody);


                    news_block.appendChild(content);
                    var details = document.createElement('div');
                    details.className = "details";


                    // var newsDescription;
                    var date = new Date(newsData.publishedAt);

                    var newsDate = document.createElement('i');
                    newsDate.innerHTML = "Published on: " + date.getUTCFullYear().toString() + "/" +
                        (date.getUTCMonth() + 1).toString() +
                        "/" + date.getUTCDate();

                    details.appendChild(newsDate);

                    content.appendChild(details);

                    // news_block.innerHTML = 'This demo DIV block was inserted into the page using JavaScript.' ;

                    newsContainer.appendChild(news_block);
                });
            });
        } else if (response.unauthorized) {
            var message = document.createElement('div');
            message.className = "alert-error";
            message.innerHTML = "Unauthorized User";
            newsContainer.appendChild(message);
        } else if (response.inactive) {
            var message = document.createElement('div');
            message.className = "alert-error";
            message.innerHTML = "Ooops! Your Account is no longer active. If you think this is an error, send your complaint to <b><span style='color: #1668fe'>contact@api4news.com</span></b>";
            newsContainer.appendChild(message);
            document.body.appendChild(googleTrnDiv);
        }

};
    });

var a = function (news) {
    Array.prototype.forEach.call(news, function (newsData) {
        news_block = document.createElement('div');
        news_block.id = counter++;
        news_block.className = "card-small trn"

        var newsImage = document.createElement('img');
        newsImage.className = "image lazyload";
        // newsImage.src =  newsData.urlToImage;
        newsImage.setAttribute('data-src', newsData.urlToImage);
        news_block.appendChild(newsImage);

        var content = document.createElement('div');
        content.className = 'newsBody';

        var newsTitle = document.createElement('h3');
        newsTitle.innerHTML = newsData.type + ' -  ' + newsData.title;
        content.appendChild(newsTitle);

        var newsAuthor = document.createElement('h6');
        newsAuthor.innerHTML = "By " + newsData.author;
        if (newsData.author === "null") {
            console.log('Author null');
        } else {
            console.log('Author Not null');
            content.appendChild(newsAuthor);
        }

        var newsBody = document.createElement('div');
        newsBody.className = "padding";

        var readFullNews = document.createElement('a');
        readFullNews.href = newsData.url;
        // readFullNews.href = "#";
        readFullNews.className = "button";
        readFullNews.id = "btn_" + counter
        readFullNews.target = "_blank";
        readFullNews.innerHTML = "Read more";

        var newsContent = document.createElement('p');
        newsContent.innerHTML = newsData != "null" ? newsData.content.substr(0, 250) + "..." : "";
        newsBody.appendChild(newsContent);
        newsBody.appendChild(readFullNews);
        // newsBody.appendChild(readMoreButton);
        content.appendChild(newsBody);


        news_block.appendChild(content);
        var details = document.createElement('div');
        details.className = "details";


        // var newsDescription;
        var date = new Date(newsData.publishedAt);

        var newsDate = document.createElement('i');
        newsDate.innerHTML = "Published on: " + date.getUTCFullYear().toString() + "/" +
            (date.getUTCMonth() + 1).toString() +
            "/" + date.getUTCDate();

        details.appendChild(newsDate);

        content.appendChild(details);

        // news_block.innerHTML = 'This demo DIV block was inserted into the page using JavaScript.' ;

        newsContainer.appendChild(news_block);
    });
}
