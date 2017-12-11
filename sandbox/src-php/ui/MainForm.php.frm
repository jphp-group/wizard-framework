{
  "title": "MainForm",
  "layout": {
      "_": "VBox",
      "align": ["center", "center"],
      "height": "100%",
      "spacing": 10,

        "_content": [
            {
              "_": "Button",
              "id": "button",
              "kind": "primary",
              "text": "Посмотреть картинку",
              "classes": ["foobar"],
              "height": 45,
              "x": 30,
              "y": 50
            },
            {
              "_": "ImageView",
              "id": "image",
              "source": "https://images-na.ssl-images-amazon.com/images/I/8191LmLELeL._SL1500_.jpg",
              "visible": false
            }
        ]
  }
}