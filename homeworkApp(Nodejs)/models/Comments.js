const mongoose = require("mongoose");
const commentSchema = mongoose.Schema({
    name: String,
    description: String,
    userId: {type: mongoose.Schema.Types.ObjectId, ref: "users"},
    homeworkId: {type: mongoose.Schema.Types.ObjectId, ref: "homework"}
});

const Comment = mongoose.model("Comment", commentSchema);


module.exports = Comment;