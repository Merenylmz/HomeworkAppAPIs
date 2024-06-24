const express = require("express");
const app = express();
const session = require("express-session");
const cookieParser = require("cookie-parser");
const mongoose = require("mongoose");
const MongoDbStore = require("connect-mongodb-session")(session);
const path = require("path");
require("dotenv").config();

const subjectRoutes = require("./routes/Subject");
const usersRoutes = require("./routes/Users");
const homeworkRoutes = require("./routes/Homework");
const commentRoutes = require("./routes/Comment");
const notificationRoutes = require("./routes/Notification");


app.use(express.json());
app.use(express.urlencoded({extended: true}));
app.use(cookieParser());
app.use(session({
    secret: process.env.PrivateKey,
    resave: false,
    saveUninitialized: false,
    store: new MongoDbStore({
        uri: process.env.MongoDbConnectionUri,
        collection: "Sessions"
    }),
    cookie: {maxAge: 1000 * 60 * 60 * 24}
}));


app.get("/", (req, res) =>{res.send("Anasayfa");});
app.use("/subjects", subjectRoutes);
app.use("/users", usersRoutes);
app.use("/homework", homeworkRoutes);
app.use("/comments", commentRoutes);
app.use("/notifications", notificationRoutes);
app.use("/uploads/profilephotos", express.static(path.join(__dirname, "public/uploads/profilephotos")));


(async()=>{
    const res = await mongoose.connect(process.env.MongoDbConnectionUri);
    if (res) {
        app.listen(3000, ()=>{
            console.log("Listening a PORT 3000");
        });
    }else{console.log("Mongo Connection Error");}
})()
