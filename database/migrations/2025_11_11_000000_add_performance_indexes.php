<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * ✅ Add performance-critical database indexes
     */
    public function up(): void
    {
        // Add index to assessments for faster filtering by is_active and sorting
        Schema::table('assessments', function (Blueprint $table) {
            // Composite index for common filtering + sorting
            if (!$this->hasIndex('assessments', 'idx_assessments_is_active_created')) {
                $table->index(['is_active', 'created_at'], 'idx_assessments_is_active_created');
            }
            // Single column index for basic is_active filtering
            if (!$this->hasIndex('assessments', 'idx_assessments_is_active')) {
                $table->index('is_active', 'idx_assessments_is_active');
            }
        });

        // Add index for assessment_user_codes for faster filtering
        if (Schema::hasTable('assessment_user_codes')) {
            Schema::table('assessment_user_codes', function (Blueprint $table) {
                if (!$this->hasIndex('assessment_user_codes', 'idx_user_codes_is_used')) {
                    $table->index('is_used', 'idx_user_codes_is_used');
                }
                if (!$this->hasIndex('assessment_user_codes', 'idx_user_codes_assessment_is_used')) {
                    $table->index(['assessment_id', 'is_used'], 'idx_user_codes_assessment_is_used');
                }
            });
        }

        // Add indexes for news table
        if (Schema::hasTable('news')) {
            Schema::table('news', function (Blueprint $table) {
                if (!$this->hasIndex('news', 'idx_news_source')) {
                    $table->index('source', 'idx_news_source');
                }
                if (!$this->hasIndex('news', 'idx_news_category')) {
                    $table->index('category', 'idx_news_category');
                }
                if (!$this->hasIndex('news', 'idx_news_is_active_created')) {
                    $table->index(['is_active', 'created_at'], 'idx_news_is_active_created');
                }
            });
        }

        // Add indexes for feedback table
        if (Schema::hasTable('feedback')) {
            Schema::table('feedback', function (Blueprint $table) {
                if (!$this->hasIndex('feedback', 'idx_feedback_status')) {
                    $table->index('status', 'idx_feedback_status');
                }
                if (!$this->hasIndex('feedback', 'idx_feedback_created')) {
                    $table->index('created_at', 'idx_feedback_created');
                }
            });
        }

        // Add indexes for schedules table
        if (Schema::hasTable('schedules')) {
            Schema::table('schedules', function (Blueprint $table) {
                if (!$this->hasIndex('schedules', 'idx_schedules_start_date')) {
                    $table->index('start_date', 'idx_schedules_start_date');
                }
            });
        }

        // Add indexes for lks_bipartits table
        if (Schema::hasTable('lks_bipartits')) {
            Schema::table('lks_bipartits', function (Blueprint $table) {
                if (!$this->hasIndex('lks_bipartits', 'idx_lks_status')) {
                    $table->index('status', 'idx_lks_status');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            if ($this->hasIndex('assessments', 'idx_assessments_is_active_created')) {
                $table->dropIndex('idx_assessments_is_active_created');
            }
            if ($this->hasIndex('assessments', 'idx_assessments_is_active')) {
                $table->dropIndex('idx_assessments_is_active');
            }
        });

        if (Schema::hasTable('assessment_user_codes')) {
            Schema::table('assessment_user_codes', function (Blueprint $table) {
                if ($this->hasIndex('assessment_user_codes', 'idx_user_codes_is_used')) {
                    $table->dropIndex('idx_user_codes_is_used');
                }
                if ($this->hasIndex('assessment_user_codes', 'idx_user_codes_assessment_is_used')) {
                    $table->dropIndex('idx_user_codes_assessment_is_used');
                }
            });
        }

        if (Schema::hasTable('news')) {
            Schema::table('news', function (Blueprint $table) {
                if ($this->hasIndex('news', 'idx_news_source')) {
                    $table->dropIndex('idx_news_source');
                }
                if ($this->hasIndex('news', 'idx_news_category')) {
                    $table->dropIndex('idx_news_category');
                }
                if ($this->hasIndex('news', 'idx_news_is_active_created')) {
                    $table->dropIndex('idx_news_is_active_created');
                }
            });
        }

        if (Schema::hasTable('feedback')) {
            Schema::table('feedback', function (Blueprint $table) {
                if ($this->hasIndex('feedback', 'idx_feedback_status')) {
                    $table->dropIndex('idx_feedback_status');
                }
                if ($this->hasIndex('feedback', 'idx_feedback_created')) {
                    $table->dropIndex('idx_feedback_created');
                }
            });
        }

        if (Schema::hasTable('schedules')) {
            Schema::table('schedules', function (Blueprint $table) {
                if ($this->hasIndex('schedules', 'idx_schedules_start_date')) {
                    $table->dropIndex('idx_schedules_start_date');
                }
            });
        }

        if (Schema::hasTable('lks_bipartits')) {
            Schema::table('lks_bipartits', function (Blueprint $table) {
                if ($this->hasIndex('lks_bipartits', 'idx_lks_status')) {
                    $table->dropIndex('idx_lks_status');
                }
            });
        }
    }

    /**
     * Helper function to check if index exists
     */
    private function hasIndex(string $table, string $index): bool
    {
        $indexes = Schema::getIndexes($table);
        return in_array($index, array_column($indexes, 'name'), true);
    }
};
