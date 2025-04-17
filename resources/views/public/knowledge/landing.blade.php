@extends('layouts.public')

@section('title', 'Knowledge Base - IT Support')

@section('styles')
<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
<link
  rel="stylesheet"
  href="https://fonts.googleapis.com/css2?display=swap&family=Noto+Sans%3Awght%40400%3B500%3B700%3B900&family=Public+Sans%3Awght%40400%3B500%3B700%3B900"
/>
<style>
  body {
    font-family: "Public Sans", "Noto Sans", sans-serif;
    background-color: #f8fafc;
  }
  .category-card {
    transition: all 0.2s ease-in-out;
  }
  .category-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  }
  .article-card {
    transition: all 0.2s ease-in-out;
  }
  .article-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  }
  .hero-section {
    background-image: linear-gradient(rgba(0, 0, 0, 0.1) 0%, rgba(0, 0, 0, 0.4) 100%), url("https://cdn.usegalileo.ai/sdxl10/e7188632-f3e1-40b9-b51c-79ca3433e9e8.png");
    background-size: cover;
    background-position: center;
    min-height: 320px;
    border-radius: 0.75rem;
  }
</style>
@endsection

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <!-- Hero Section with Search -->
      <div class="mb-5 hero-section d-flex flex-column justify-content-end p-4 p-md-5">
        <h1 class="text-white fw-bold mb-4 display-5">Browse our help articles or search below</h1>
        <form action="{{ route('knowledge.search') }}" method="GET" class="mb-3">
          <div class="input-group input-group-lg bg-white rounded-pill overflow-hidden" style="max-width: 600px;">
            <span class="input-group-text bg-white border-0 ps-4">
              <i class="fas fa-search text-secondary"></i>
            </span>
            <input 
              type="text" 
              class="form-control border-0 shadow-none" 
              name="query" 
              placeholder="Search for solutions..." 
              aria-label="Search"
            >
            <button class="btn btn-primary px-4 m-1 rounded-pill" type="submit">Search</button>
          </div>
        </form>
      </div>

      <!-- Categories Section -->
      <div class="mb-5">
        <h2 class="fw-bold mb-4">Browse articles by topic</h2>
        <div class="row g-4">
          @forelse($categories as $category)
          <div class="col-md-4 col-sm-6">
            <a href="{{ route('knowledge.category', $category->slug) }}" class="text-decoration-none">
              <div class="category-card h-100 p-4 border rounded-3 bg-white d-flex flex-column gap-3">
                <div class="text-primary">
                  @if($category->icon)
                  <i class="fas fa-{{ $category->icon }} fa-2x"></i>
                  @else
                  <i class="fas fa-folder fa-2x"></i>
                  @endif
                </div>
                <div>
                  <h3 class="h5 fw-bold">{{ $category->name }}</h3>
                  <p class="text-secondary small mb-2">{{ Str::limit($category->description, 80) }}</p>
                  <div class="d-flex justify-content-between align-items-center">
                    <span class="text-primary small">View articles</span>
                    <span class="badge bg-light text-dark">{{ $category->articles_count }} articles</span>
                  </div>
                </div>
              </div>
            </a>
          </div>
          @empty
          <div class="col-12">
            <div class="alert alert-info">
              <i class="fas fa-info-circle me-2"></i> No categories have been added to the knowledge base yet.
            </div>
          </div>
          @endforelse
        </div>
      </div>

      <!-- Popular Articles -->
      <div class="mb-5">
        <h2 class="fw-bold mb-4">Most popular articles</h2>
        <div class="row g-4">
          @forelse($popularArticles as $article)
          <div class="col-md-4">
            <a href="{{ route('knowledge.article', ['categorySlug' => $article->category ? $article->category->slug : 'uncategorized', 'articleSlug' => $article->slug]) }}" class="text-decoration-none">
              <div class="article-card h-100 p-4 border rounded-3 bg-white">
                <div class="d-flex align-items-center mb-3">
                  <i class="fas fa-file-alt text-primary me-2"></i>
                  <h3 class="h6 fw-bold mb-0">{{ $article->title }}</h3>
                </div>
                @if($article->meta_description)
                <p class="text-secondary small mb-3">{{ Str::limit($article->meta_description, 100) }}</p>
                @endif
                <div class="d-flex align-items-center text-secondary small">
                  <span><i class="far fa-eye me-1"></i> {{ $article->views_count }}</span>
                  <span class="ms-3"><i class="far fa-calendar me-1"></i> {{ $article->published_at->format('M d, Y') }}</span>
                </div>
              </div>
            </a>
          </div>
          @empty
          <div class="col-12">
            <div class="alert alert-info">
              <i class="fas fa-info-circle me-2"></i> No popular articles found.
            </div>
          </div>
          @endforelse
        </div>
      </div>

      <!-- Recent Articles -->
      <div class="mb-5">
        <h2 class="fw-bold mb-4">Recent articles</h2>
        <div class="row g-4">
          @forelse($recentArticles as $article)
          <div class="col-md-4">
            <a href="{{ route('knowledge.article', ['categorySlug' => $article->category ? $article->category->slug : 'uncategorized', 'articleSlug' => $article->slug]) }}" class="text-decoration-none">
              <div class="article-card h-100 p-4 border rounded-3 bg-white">
                <div class="d-flex align-items-center mb-3">
                  <i class="fas fa-file-alt text-primary me-2"></i>
                  <h3 class="h6 fw-bold mb-0">{{ $article->title }}</h3>
                </div>
                @if($article->meta_description)
                <p class="text-secondary small mb-3">{{ Str::limit($article->meta_description, 100) }}</p>
                @endif
                <div class="d-flex align-items-center text-secondary small">
                  <span><i class="far fa-clock me-1"></i> {{ $article->published_at->diffForHumans() }}</span>
                </div>
              </div>
            </a>
          </div>
          @empty
          <div class="col-12">
            <div class="alert alert-info">
              <i class="fas fa-info-circle me-2"></i> No recent articles found.
            </div>
          </div>
          @endforelse
        </div>
      </div>

      <!-- Frequently Asked Questions -->
      <div class="mb-5">
        <h2 class="fw-bold mb-4">Frequently asked questions</h2>
        <div class="accordion rounded-3 overflow-hidden" id="faqAccordion">
          @php
            // Dummy FAQ data since we don't have a real FAQ model yet
            $faqs = [
              [
                'question' => 'What are the device compatibility requirements for the latest software updates?',
                'answer' => 'To ensure your device can successfully install and run the latest software updates, it must meet certain hardware and software specifications. Ensure that your device has at least 4GB of RAM and 64GB of available storage. Your operating system should also be updated to the latest version supported by your device manufacturer.'
              ],
              [
                'question' => 'How can I enhance my device\'s performance?',
                'answer' => 'There are several ways to enhance your device\'s performance: regularly update your software, uninstall unused applications, clear cache files, ensure sufficient storage space, and consider adding more RAM if your device allows for it.'
              ],
              [
                'question' => 'What should I do if I face a blue screen error?',
                'answer' => 'If you encounter a blue screen error, first note down any error code displayed. Restart your device and check if the issue persists. If it does, try booting in safe mode, running system diagnostics, checking for recent hardware or software changes, and updating your drivers. If the problem continues, contact our support team.'
              ]
            ];
          @endphp

          @foreach($faqs as $index => $faq)
          <div class="accordion-item border-0 mb-3">
            <div class="accordion-header">
              <button class="accordion-button bg-white shadow-sm rounded-3 {{ $index > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="faq{{ $index }}">
                <span class="fw-semibold">{{ $faq['question'] }}</span>
              </button>
            </div>
            <div id="faq{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" data-bs-parent="#faqAccordion">
              <div class="accordion-body text-secondary bg-white shadow-sm rounded-bottom">
                {{ $faq['answer'] }}
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>

      <!-- Need Support -->
      <div class="border rounded-3 p-4 p-md-5 text-center bg-primary text-white mb-5">
        <h3 class="fw-bold mb-3">Can't find what you're looking for?</h3>
        <p class="mb-4">If you couldn't find a solution to your problem, submit a support ticket and our team will assist you.</p>
        <a href="{{ route('public.create.ticket') }}" class="btn btn-light btn-lg px-4">Submit a Ticket</a>
      </div>
    </div>
  </div>
</div>
@endsection 