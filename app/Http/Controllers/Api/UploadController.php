<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function upload_journals(Request $request)
    {


        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id', // Check if user_id exists
            'paper_title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'publication_name' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'doi' => 'nullable|string|max:255',
            'authors' => 'nullable|string',
            'research_interest' => 'nullable|string|max:255',
            'section' => 'nullable|string|max:255',
            'link' => 'nullable|url',
            'article' => 'nullable|file|mimes:pdf|max:20480', // Allow only PDF files (max 20MB)
        ], [
            'user_id.exists' => 'The selected user does not exist.', // Custom error message
        ]);
        

        // If validation fails, return the errors
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 404);
        }

        // Handle the file upload for the article (PDF)
        $articlePath = null;
        if ($request->hasFile('article')) {
            $articlePath = $request->file('article')->store('articles', 'public');
        }

        // Store the data in the articles table
        $article = Article::create([
            'user_id' => $request->input('user_id'),
            'paper_title' => $request->input('paper_title'),
            'abstract' => $request->input('abstract'),
            'publication_name' => $request->input('publication_name'),
            'year' => $request->input('year'),
            'doi' => $request->input('doi'),
            'authors' => $request->input('authors'),
            'research_interest' => $request->input('research_interest'),
            'section' => $request->input('section'),
            'link' => $request->input('link'),
            'article' => $articlePath, // Store the file path
        ]);

        
        return response()->json([
            'message' => 'Journal uploaded successfully!',
            'article' => $article
        ], 200);
    }

    public function delete_journal(Request $request)
    {
     
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'id' => 'required|exists:articles,id',
        ], [
            'user_id.exists' => 'The selected user does not exist.',
            'id.exists' => 'The article does not exist.',
        ]);
    

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }
    
        // Find the article by ID
        $article = Article::find($request->input('id'));
    
        if (!$article) {
            return response()->json([
                'message' => 'Journal not found.'
            ], 404);
        }

        if ($article->user_id !== $request->input('user_id')) {
            return response()->json([
                'message' => 'Unauthorized to delete this journal.'
            ], 404 );
        }
    
        // Delete the file from storage if it exists
        if ($article->article) {
            Storage::disk('public')->delete($article->article);
        }
    
        // Delete the journal record from the database
        $article->delete();
    
        // Return a success response
        return response()->json([
            'message' => 'Journal deleted successfully.'
        ], 200);
    }

    public function delete_post(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'id' => 'required|exists:posts,id',
        ], [
            'user_id.exists' => 'The selected user does not exist.',
            'id.exists' => 'The article does not exist.',
        ]);
    
        // If validation fails, return the errors
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }
    
        // Find the article by ID
        $article = Post::find($request->input('id'));
    
        if (!$article) {
            return response()->json([
                'message' => 'Post not found.'
            ], 404);
        }
    

        if ($article->user_id !== $request->input('user_id')) {
            return response()->json([
                'message' => 'Unauthorized to delete this journal.'
            ], 403);
        }
        // Delete the file from storage if it exists
        if ($article->article) {
            Storage::disk('public')->delete($article->article);
        }
    
        // Delete the journal record from the database
        $article->delete();
    
        // Return a success response
        return response()->json([
            'message' => 'Post deleted successfully.'
        ], 200);
    }
    

    public function upload_posts(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'description' => 'required|string|max:255',
            'post' => 'nullable|file|mimes:pdf,png,jpg,jpeg,gif|max:20480',
        ], [
            'user_id.exists' => 'The selected user does not exist.',
            'post.mimes' => 'The post must be a file of type: pdf, png, jpg, jpeg, gif.',
        ]);
    
        // If validation fails, return the errors
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 404);
        }
    
        // Handle the file upload for the post
        $postPath = null;
        if ($request->hasFile('post')) {
            $postPath = $request->file('post')->store('posts', 'public');
        }
    
        // Store the data in the posts table
        $post = Post::create([
            'user_id' => $request->input('user_id'),
            'description' => $request->input('description'),
            'post' => $postPath, // Store the file path
        ]);
    
        // Return a success response
        return response()->json([
            'message' => 'Post uploaded successfully!',
            'post' => $post
        ], 200);
    }
    
}