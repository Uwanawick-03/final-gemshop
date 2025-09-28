<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobIssue extends Model
{
    protected $fillable = [
        'job_number',
        'item_id',
        'craftsman_id',
        'issue_type', // 'defect', 'delay', 'quality', 'material', 'other'
        'priority', // 'low', 'medium', 'high', 'urgent'
        'issue_date',
        'description',
        'status', // 'open', 'in_progress', 'resolved', 'closed'
        'assigned_to',
        'resolved_by',
        'resolution_notes',
        'resolved_date',
        'estimated_completion',
        'actual_completion'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'resolved_date' => 'date',
        'estimated_completion' => 'date',
        'actual_completion' => 'date'
    ];

    // Relationships
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function craftsman()
    {
        return $this->belongsTo(Craftsman::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function resolvedBy()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    // Accessors
    public function getDisplayNameAttribute()
    {
        return "Job Issue #{$this->job_number} - {$this->item->name}";
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'open' => 'warning',
            'in_progress' => 'info',
            'resolved' => 'success',
            'closed' => 'secondary'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getPriorityBadgeAttribute()
    {
        $badges = [
            'low' => 'success',
            'medium' => 'info',
            'high' => 'warning',
            'urgent' => 'danger'
        ];

        return $badges[$this->priority] ?? 'secondary';
    }

    public function getResolutionTimeAttribute()
    {
        if ($this->issue_date && $this->resolved_date) {
            return $this->issue_date->diffInDays($this->resolved_date);
        }
        return null;
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['high', 'urgent']);
    }
}
