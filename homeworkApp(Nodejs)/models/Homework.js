const mongoose = require("mongoose");

const commentSchema = mongoose.Schema({
    commentId: {type: mongoose.Schema.Types.ObjectId, ref: "comments"},
    description: {type: String, default: null}
});

const homeworkSchema = mongoose.Schema({
    title: String,
    description: String,
    userId: {type: mongoose.Schema.Types.ObjectId, ref: "users"},
    subjectId: {type: mongoose.Schema.Types.ObjectId, ref: "subjects"},
    comments: {type: [commentSchema], default:[]},

});

const Homework = mongoose.model("Homework", homeworkSchema);

module.exports = Homework;