<?php

namespace Tests\Unit;

use App\Adminapi\Logic\WorkbenchLogic;
use PHPUnit\Framework\TestCase;

class IncompleteWorkTest extends TestCase
{
    /**
     * Test that incomplete work method returns proper structure.
     */
    public function test_incomplete_work_returns_proper_structure(): void
    {
        $result = WorkbenchLogic::incompleteWork();
        
        // Test summary structure
        $this->assertArrayHasKey('summary', $result);
        $this->assertArrayHasKey('total_tasks', $result['summary']);
        $this->assertArrayHasKey('completed_tasks', $result['summary']);
        $this->assertArrayHasKey('completion_rate', $result['summary']);
        $this->assertArrayHasKey('priority_tasks', $result['summary']);
        
        // Test categories structure
        $this->assertArrayHasKey('categories', $result);
        $this->assertIsArray($result['categories']);
        
        // Test that each category has the required structure
        foreach ($result['categories'] as $category) {
            $this->assertArrayHasKey('name', $category);
            $this->assertArrayHasKey('tasks', $category);
            $this->assertIsArray($category['tasks']);
            
            // Test that each task has the required structure
            foreach ($category['tasks'] as $task) {
                $this->assertArrayHasKey('id', $task);
                $this->assertArrayHasKey('title', $task);
                $this->assertArrayHasKey('description', $task);
                $this->assertArrayHasKey('priority', $task);
                $this->assertArrayHasKey('status', $task);
                $this->assertArrayHasKey('progress', $task);
                $this->assertArrayHasKey('estimated_hours', $task);
                $this->assertArrayHasKey('dependencies', $task);
                
                // Test data types
                $this->assertIsInt($task['id']);
                $this->assertIsString($task['title']);
                $this->assertIsString($task['description']);
                $this->assertIsString($task['priority']);
                $this->assertIsString($task['status']);
                $this->assertIsInt($task['progress']);
                $this->assertIsInt($task['estimated_hours']);
                $this->assertIsArray($task['dependencies']);
                
                // Test value constraints
                $this->assertGreaterThanOrEqual(0, $task['progress']);
                $this->assertLessThanOrEqual(100, $task['progress']);
                $this->assertContains($task['priority'], ['high', 'medium', 'low']);
                $this->assertContains($task['status'], ['todo', 'in_progress', 'testing']);
            }
        }
        
        // Test recent updates structure
        $this->assertArrayHasKey('recent_updates', $result);
        $this->assertIsArray($result['recent_updates']);
        
        foreach ($result['recent_updates'] as $update) {
            $this->assertArrayHasKey('date', $update);
            $this->assertArrayHasKey('message', $update);
            $this->assertIsString($update['date']);
            $this->assertIsString($update['message']);
        }
    }
    
    /**
     * Test that incomplete work contains expected number of categories.
     */
    public function test_incomplete_work_has_expected_categories(): void
    {
        $result = WorkbenchLogic::incompleteWork();
        
        // Should have at least 3 categories based on our implementation
        $this->assertGreaterThanOrEqual(3, count($result['categories']));
        
        $categoryNames = array_column($result['categories'], 'name');
        $this->assertContains('功能开发', $categoryNames);
        $this->assertContains('代码优化', $categoryNames);
        $this->assertContains('API接口迁移', $categoryNames);
    }
}